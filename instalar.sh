#!/bin/bash

echo "🔧 Iniciando instalación del sistema de facturación en Ubuntu 24.04"

# 1. Crear usuario facturacion
echo "👤 Creando usuario facturacion..."
useradd -m -s /bin/bash facturacion
echo "facturacion:12345" | chpasswd
usermod -aG sudo facturacion
echo "✅ Usuario creado con acceso SFTP y SSH."

# 2. Actualizar sistema e instalar dependencias básicas
echo "📦 Actualizando sistema e instalando dependencias base..."
apt update && apt upgrade -y

apt install -y git curl wget unzip build-essential libssl-dev libffi-dev libxml2-dev libxslt1-dev \
    libjpeg-dev libpq-dev mariadb-server openssh-server ufw apache2 php php-mysql php-cli php-curl \
    php-xml php-mbstring php-zip php-soap php-bcmath php-gd php-intl libreadline-dev zlib1g-dev \
    libbz2-dev libncurses5-dev libsqlite3-dev liblzma-dev libncursesw5-dev libxml2-utils

echo "✅ Dependencias básicas instaladas."

# 3. Instalar OpenSSL 1.1 (downgrade a versión compatible)
echo "🐍 Instalando OpenSSL 1.1 específico..."
cd /tmp
wget http://mirrors.kernel.org/ubuntu/pool/main/o/openssl/libssl1.1_1.1.1f-1ubuntu2_amd64.deb
dpkg -i libssl1.1_1.1.1f-1ubuntu2_amd64.deb

wget http://mirrors.kernel.org/ubuntu/pool/main/o/openssl/openssl_1.1.1f-1ubuntu2_amd64.deb
dpkg -i openssl_1.1.1f-1ubuntu2_amd64.deb

echo "🔧 Configurando OpenSSL con legacy provider..."
cat << EOF > /etc/ssl/openssl.cnf
openssl_conf = openssl_init

[openssl_init]
providers = provider_sect

[provider_sect]
base = base_sect
legacy = legacy_sect

[base_sect]
activate = 1

[legacy_sect]
activate = 1
EOF

openssl version

# 4. Configurar alias python=python3.10 en bashrc
echo "🐍 Configurando alias python=python3.10..."
echo "alias python=python3.10" >> /root/.bashrc
echo "alias python=python3.10" >> /home/facturacion/.bashrc

# Aplicar alias en sesión actual
source /root/.bashrc

# 5. Quitar Python 3.12 y paquetes relacionados (precaución)
echo "⚠️ Eliminando Python 3.12 y paquetes asociados..."
apt remove --purge -y python3.12 python3.12-minimal python3.12-dev libpython3.12-stdlib libpython3.12-minimal libpython3.12-dev libpython3.12t64 || true
apt autoremove -y

# 6. Instalar dependencias para compilar Python
echo "🐍 Instalando dependencias para compilar Python 3.10..."
apt install -y build-essential wget libssl-dev zlib1g-dev libncurses5-dev libncursesw5-dev libreadline-dev libsqlite3-dev libffi-dev libbz2-dev

# 7. Descargar, compilar e instalar Python 3.10.11 desde fuente
echo "🐍 Descargando y compilando Python 3.10.11..."
cd /usr/src
wget https://www.python.org/ftp/python/3.10.11/Python-3.10.11.tgz
tar xzf Python-3.10.11.tgz
cd Python-3.10.11
./configure --enable-optimizations
make -j $(nproc)
make altinstall

# Verificar versión instalada
python3.10 --version

# 8. Instalar pip para python3.10
echo "📦 Instalando pip para Python 3.10..."
curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
python3.10 get-pip.py
rm get-pip.py

# 9. Instalar xades-bes-sri-ec globalmente sin entorno virtual
echo "📂 Instalando xades-bes-sri-ec globalmente..."
pip3 install --upgrade pip
pip3 install git+https://github.com/alfredo138923/xades-bes-sri-ec.git

# 10. Instalar php y módulo apache (si no están)
echo "🐘 Instalando PHP y módulo Apache..."
apt install -y php libapache2-mod-php php-mysql

# 11. Configurar servicio SSH (activo por defecto)
echo "🔐 Configurando SSH..."
systemctl enable ssh
systemctl start ssh

# 12. Configurar firewall para permitir tráfico necesario
echo "🛡️ Configurando UFW..."
ufw allow OpenSSH
ufw allow 80
ufw allow 3306
ufw --force enable
echo "✅ UFW configurado."

# 13. Configurar MariaDB para aceptar conexiones remotas
echo "🛠️ Configurando MariaDB para acceso remoto..."
sed -i "s/^bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mariadb.conf.d/50-server.cnf
systemctl restart mariadb

# 14. Crear usuario remoto en MariaDB
echo "🛠️ Creando usuario MariaDB remoto..."
mysql -u root <<EOF
CREATE USER IF NOT EXISTS 'facturacion'@'%' IDENTIFIED BY '12345';
GRANT ALL PRIVILEGES ON *.* TO 'facturacion'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EOF

# 15. Finalización
echo "✅ Instalación completada."
echo "📝 Accesos:"
echo " - SSH/SFTP usuario: facturacion, contraseña: 12345"
echo " - Base de datos: host=<ip_del_servidor>, usuario=facturacion, pass=12345"
echo " - Proyecto en: /var/www/html/facturacion"
echo " - Para usar python: ejecuta 'python' (alias para python3.10)"


# 16. Crear script prueba.py para testear firma
echo "📄 Creando script prueba.py para probar la firma electrónica..."
cat << 'EOF' > /var/www/html/facturacion/prueba.py
from xades_bes_sri_ec import xades
import os

def main():
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    ruta_certificado = os.path.join(BASE_DIR, "assets/doc/certificado/6069924_identity.p12")
    clave_certificado = "Vini1985258"
    ruta_xml_original = os.path.join(BASE_DIR, "assets/doc/facturas_generadas/prueba.xml")
    ruta_xml_firmado = os.path.join(BASE_DIR, "assets/doc/facturas_firmadas/factura_firmada.xml")

    try:
        xades.firmar_comprobante(ruta_certificado, clave_certificado, ruta_xml_original, ruta_xml_firmado)
        print("✅ Firma completada. XML firmado guardado en:", ruta_xml_firmado)
    except Exception as e:
        print("❌ Error durante la firma:", str(e))

if __name__ == "__main__":
    main()
EOF

chown facturacion:facturacion /var/www/html/facturacion/prueba.py
chmod +x /var/www/html/facturacion/prueba.py

echo "✅ Script prueba.py creado. Puedes ejecutar: python /var/www/html/facturacion/prueba.py para probar la firma, primero copia las carpetas y archivos dentro de facturacion"

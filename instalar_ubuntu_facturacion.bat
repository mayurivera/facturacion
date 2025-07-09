@echo off
setlocal

echo =====================================================
echo 🐧 Instalando Ubuntu y configurando entorno WSL en Windows 11
echo =====================================================

REM 1. Habilitar los componentes necesarios para WSL y Virtual Machine Platform
echo 🔧 Habilitando WSL y Virtual Machine Platform...
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

REM 2. Establecer WSL 2 como versión predeterminada
echo ⚙️ Estableciendo WSL 2 como predeterminado...
wsl --set-default-version 2

REM 3. Descargar Ubuntu desde Microsoft Store (vía wsl.exe)
echo 📦 Instalando Ubuntu 22.04 desde Microsoft Store...
wsl --install -d Ubuntu

echo ✅ Ubuntu instalado. Espera a que se configure la primera vez que abras la consola.

echo =====================================================
echo 🔐 Una vez que abras Ubuntu, crea el usuario 'facturacion'
echo y luego ejecuta tu script de instalación de facturación:
echo
echo     sudo bash /mnt/c/.../instalador_facturacion.sh
echo
echo Asegúrate de mover tu script a una carpeta visible para Ubuntu.
echo =====================================================

pause

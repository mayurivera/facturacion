import sys
from xades_bes_sri_ec import xades

if len(sys.argv) != 5:
    print("Uso: python firmar_xml.py <certificado.p12> <clave> <ruta_xml_entrada> <ruta_xml_salida>")
    sys.exit(1)

ruta_certificado = sys.argv[1]
clave_certificado = sys.argv[2]
ruta_xml_original = sys.argv[3]
ruta_xml_firmado = sys.argv[4]

try:
    xades.firmar_comprobante(ruta_certificado, clave_certificado, ruta_xml_original, ruta_xml_firmado)
    print("✅ Firma completada. XML firmado guardado en:", ruta_xml_firmado)
except Exception as e:
    print("❌ Error durante la firma:", str(e))
    sys.exit(2)

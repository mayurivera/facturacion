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

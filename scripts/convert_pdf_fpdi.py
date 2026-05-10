"""
Script: convert_pdf_fpdi.py
Tujuan: Konversi PDF terkompresi (PDF 1.5+) ke format yang kompatibel dengan FPDI gratis.
        Menonaktifkan object streams / cross-reference streams.
Penggunaan: python convert_pdf_fpdi.py <input_path> <output_path>
"""

import sys
import pikepdf

def convert(input_path, output_path):
    try:
        pdf = pikepdf.open(input_path)
        # Simpan ulang dengan object_stream_mode=disable
        # Ini mengubah cross-reference streams ke cross-reference tables (PDF 1.4 kompatibel)
        pdf.save(
            output_path,
            object_stream_mode=pikepdf.ObjectStreamMode.disable,
            compress_streams=False,
            preserve_pdfa=False,
        )
        print("SUCCESS")
    except Exception as e:
        print("ERROR: " + str(e))
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("ERROR: Usage: python convert_pdf_fpdi.py <input> <output>")
        sys.exit(1)
    convert(sys.argv[1], sys.argv[2])

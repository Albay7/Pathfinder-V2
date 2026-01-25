import sys
from pathlib import Path

# Ensure stdout can handle UTF-8 characters on Windows consoles
try:
    if hasattr(sys.stdout, "reconfigure"):
        sys.stdout.reconfigure(encoding="utf-8")
except Exception:
    pass

pdf_path = Path(r"c:\Users\Hendrix\OneDrive\Desktop\Projects\PathfinderApp\PATHFINDER.V2.pdf")

try:
    from PyPDF2 import PdfReader
except ImportError:
    print("ERROR: PyPDF2 is not installed. Please install it with 'python -m pip install PyPDF2' and run again.", file=sys.stderr)
    sys.exit(1)

if not pdf_path.exists():
    print(f"ERROR: PDF not found at {pdf_path}", file=sys.stderr)
    sys.exit(1)

reader = PdfReader(str(pdf_path))

for i, page in enumerate(reader.pages):
    try:
        text = page.extract_text() or ""
    except Exception as e:
        text = f"\n[Page {i+1} extraction error: {e}]\n"
    print(f"\n\n===== PAGE {i+1} =====\n")
    print(text)

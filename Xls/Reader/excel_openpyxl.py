from openpyxl import load_workbook
import json
import sys
import os
import argparse

def run(argv):
  parser = argparse.ArgumentParser()
  parser.add_argument('--size')
  parser.add_argument('--start')
  parser.add_argument('--action')
  parser.add_argument('--file')
  args = parser.parse_args()

  if False == os.path.isfile(args.file):
    print("File does not exist")
    sys.exit(1)

  workbook = load_workbook(args.file, read_only=True)
  sheet = workbook.active
  if args.action == "count":
    print(sheet.max_row)
  elif args.action == "read":
    rows = []
    for row in sheet.iter_rows(row_offset=int(args.start) - 1):
        current_row_read = []
        for cell in row:
            value = cell.value
            if isinstance(value, (int, long)):
                current_row_read.append(str(value))
            else:
                current_row_read.append(value.encode('utf-8'))
        rows.append(current_row_read)
        if len(rows) >= int(args.size):
          break
    print(json.dumps(rows))
  else:
    print("Unknown command")
    sys.exit(1)

if __name__ == "__main__":
   run(sys.argv[1:])

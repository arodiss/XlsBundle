import xlrd
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
  parser.add_argument('--max-empty-rows', dest="max_empty_rows")
  args = parser.parse_args()

  if False == os.path.isfile(args.file):
    print("File does not exist")
    sys.exit(1)

  workbook = xlrd.open_workbook(args.file)
  sheet = workbook.sheet_by_index(0)
  if args.action == "count":
    print(sheet.nrows)

  elif args.action == "read":
    reached_end = False
    rows = []
    while len(rows) < int(args.size) and reached_end == False:
      try:
        rows.append(sheet.row_values(int(args.start) + len(rows) - 1))
      except IndexError:
        reached_end = True
    print(json.dumps(rows))

  else:
    print("Unknown command")
    sys.exit(1)

if __name__ == "__main__":
   run(sys.argv[1:])

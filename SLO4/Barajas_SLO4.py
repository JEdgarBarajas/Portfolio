file_name = input()
info = open(file_name, "r")
out_keys = open("output_keys.txt", "w")
out_titles = open("output_titles.txt", "w")

shows = {}
titles = []
key_list = []

lines = info.readlines()
for i in range(0, len(lines), 2):
    seasons = lines[i].strip("\n")
    titles.append(lines[i+1].strip("\n"))
    if(seasons in shows):
        add_on = lines[i + 1].strip("\n")
        shows[seasons] = f"{shows[seasons]}; {add_on}"
    else:
        shows[seasons] = lines[i + 1].strip("\n")
        key_list.append(int(seasons))

titles.sort()
titles.reverse()
key_list.sort()
key_list.reverse()

for key in key_list:
    val = str(key)
    out_keys.write(f"{key}: {shows[val]}\n")


for index in titles:
   out_titles.write(f"{index}\n")

info.close()
out_keys.close()
out_titles.close()
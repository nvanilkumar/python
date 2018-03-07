import requests
 
#load image from file
file = open('2.png', 'rb')
headers = {'content-type': 'multipart/form-data'}
 
payload = {'apikey': 'ab6a34eeaf26602b', 'lang': 'eng'}
files = {'file': file}
r = requests.post("http://www.bitocr.com/api", data=payload, files=files)
result = r.json()
if result['error'] == 0:
    #success
    print(result['result'])
else:
    #failed
    print("Error #" + str(result['error_code']) + " " + result['error_message'])
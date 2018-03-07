import pyautogui
message=pyautogui.prompt('This lets the user type in a string and press OK.')
print message

text="Enter your PVN and a response no the gnd challenge [A3] [H5] [11] usmg a \
card with sanal number 19130 (PVN sue: 4).\
\
Server: E3 hrtns://xvrewddc.Toyota.~:om\
\
Next Code: |\
\
Cents?\
\
5 Read the Docs\
\
?.?\"iT;l!x";
print text

index=text.find('challenge ')
if index > 0:
	print index
	#print "hello"
	lenth = int(index+10)
	print text[lenth:]
	inputText=text[lenth:].lower()
	print inputText[11]+inputText[12]
	#keyText =str(matrix[inputText[1],inputText[2]])+str(matrix[inputText[6],inputText[7]])+str(matrix[inputText[10],inputText[11]])
	#print keyText
else :
	print "text not found image error"

'''
#conver image to base 64 encode
import base64
 
with open("2.png", "rb") as imageFile:
    str = base64.b64encode(imageFile.read())
    print str
'''
import pyautogui
import time

pyautogui.hotkey('win', 'd');
pyautogui.doubleClick(37 ,127);

print("This prints once a minute.")
time.sleep(3) 
print("action done");
#pyautogui.moveTo(100, 100, duration=0.25)
pyautogui.doubleClick(255 ,154);
time.sleep(5) 
pyautogui.typewrite('Toyota1234');
pyautogui.hotkey('enter');
print("enter done");

#create screen shot
#time.sleep(5)
#im1 = pyautogui.screenshot(region=(532,334,888,368))
#im2 = pyautogui.screenshot('my_screenshot.png')
print("image done");

#key file

matrix = {}
matrix['a','1']='d'
matrix['a','2']='0'
matrix['a','3']='1'
matrix['a','4']='q'
matrix['a','5']='t'

 
matrix['b','1']='n'
matrix['b','2']='y'
matrix['b','3']='q'
matrix['b','4']='n'
matrix['b','5']='w'

matrix['c','1']='k'
matrix['c','2']='x'
matrix['c','3']='y'
matrix['c','4']='6'
matrix['c','5']='v'

matrix['d','1']=0
matrix['d','2']='p'
matrix['d','3']=1
matrix['d','4']=8
matrix['d','5']=0

matrix['e','1']='x'
matrix['e','2']='v'
matrix['e','3']='c'
matrix['e','4']='k'
matrix['e','5']='j'

matrix['f','1']='j'
matrix['f','2']='e'
matrix['f','3']='0'
matrix['f','4']='q'
matrix['f','5']='k'


matrix['g','1']='7'
matrix['g','2']='w'
matrix['g','3']='x'
matrix['g','4']='f'
matrix['g','5']='2'

matrix['h','1']='q'
matrix['h','2']='1'
matrix['h','3']='8'
matrix['h','4']='n'
matrix['h','5']='y'

matrix['i','1']='y'
matrix['i','2']='e' 
matrix['i','3']='x' 
matrix['i','4']='k'
matrix['i','5']='1'

matrix['j','1']='q'
matrix['j','2']='d'
matrix['j','3']='6'
matrix['j','4']='e'
matrix['j','5']='j'

time.sleep(3)

message=pyautogui.prompt('This lets the user type in a string and press OK.')
print message
inputText=message.lower()
keyText =str(matrix[inputText[0],inputText[1]])+str(matrix[inputText[3],inputText[4]])+str(matrix[inputText[6],inputText[7]])
print keyText
keyText= "4321"+keyText
pyautogui.typewrite(keyText);
pyautogui.hotkey('enter');
print "End of the code"


#pyautogui.doubleClick(270 ,144);
#106 451
#270 144
''' 

 
'''	

'''
for i in range(10):
	pyautogui.moveTo(100, 100, duration=0.25)
	pyautogui.moveTo(200, 100, duration=0.25)
	pyautogui.moveTo(200, 200, duration=0.25)
	pyautogui.moveTo(100, 200, duration=0.25)
'''	

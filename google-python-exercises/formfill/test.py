import pyautogui


matrix = {}
matrix['a','1']=8
matrix['a','2']='q'
matrix['a','3']='c'
matrix['a','4']=8
matrix['a','5']=1

 
matrix['b','1']=5
matrix['b','2']='j'
matrix['b','3']='h'
matrix['b','4']=3
matrix['b','5']='m'

matrix['c','1']='j'
matrix['c','2']='k'
matrix['c','3']='5'
matrix['c','4']='m'
matrix['c','5']=1

matrix['d','1']=1
matrix['d','2']='k'
matrix['d','3']=9
matrix['d','4']='n'
matrix['d','5']='e'

matrix['e','1']='v'
matrix['e','2']='c'
matrix['e','3']='f'
matrix['e','4']='f'
matrix['e','5']=7

matrix['f','1']='r'
matrix['f','2']=8
matrix['f','3']='j'
matrix['f','4']='t'
matrix['f','5']='j'


matrix['g','1']='c'
matrix['g','2']='e'
matrix['g','3']='r'
matrix['g','4']='v'
matrix['g','5']=4

matrix['h','1']=3
matrix['h','2']='f'
matrix['h','3']='y'
matrix['h','4']=7
matrix['h','5']=0

matrix['i','1']='x'
matrix['i','2']=9
matrix['i','3']=4
matrix['i','4']='f'
matrix['i','5']=9

matrix['j','1']=6
matrix['j','2']=4
matrix['j','3']='r'
matrix['j','4']='c'
matrix['j','5']='n'

 
message=pyautogui.prompt('This lets the user type in a string and press OK.')
print message
inputText=message.lower()
keyText =str(matrix[inputText[0],inputText[1]])+str(matrix[inputText[3],inputText[4]])+str(matrix[inputText[6],inputText[7]])
print keyText
# encoding: utf-8

import random
import sys


class Node:

	def __init__(self, xpos=0, ypos=0, top=False, left=False, bottom=False, right=False):
		self.top = top
		self.bottom = bottom
		self.left = left
		self.right = right
		self.xpos = xpos
		self.ypos = ypos

	def count(self):
		sides = 0
		if self.top:
			sides += 1
		if self.bottom:
			sides += 1
		if self.left:
			sides += 1
		if self.right:
			sides += 1
		return sides


class Tree:

	branchpercent = 30
	turnpercent = 40

	def __init__(self, size=7, startx=4, starty=1):
		self.map = [[Node(xpos=j, ypos=i) for i in range(size)] for j in range(size)]
		self.endpoints = [{"x": (size//2), "y": (size//2), "dead": False}]
		self.map[(size//2)][(size//2)].top = True
		self.size = size

	def movepoint(self, point, direction):
		if (direction == "down"):
			if (point["y"] < (self.size - 1)):
				if (self.map[point["x"]][point["y"]+1].count() == 0):
					self.map[point["x"]][point["y"]].bottom = True
					self.map[point["x"]][point["y"]+1].top = True
					point["y"] += 1
					return True
		if (direction == "up"):
			if (point["y"] > 0):
				if (self.map[point["x"]][point["y"]-1].count() == 0):
					self.map[point["x"]][point["y"]].top = True
					self.map[point["x"]][point["y"]-1].bottom = True
					point["y"] -= 1
					return True
		if (direction == "right"):
			if (point["x"] < (self.size - 1)):
				if (self.map[point["x"]+1][point["y"]].count() == 0):
					self.map[point["x"]][point["y"]].right = True
					self.map[point["x"]+1][point["y"]].left = True
					point["x"] += 1
					return True
		if (direction == "left"):
			if (point["x"] > 0):
				if (self.map[point["x"]-1][point["y"]].count() == 0):
					self.map[point["x"]][point["y"]].left = True
					self.map[point["x"]-1][point["y"]].right = True
					point["x"] -= 1
					return True
		return False

	def randomside(self, point):
		ways = []
		if (point["y"] < (self.size - 1)):
			if (self.map[point["x"]][point["y"]+1].count() == 0):
				ways.append("down")
		if (point["y"] > 0):
			if (self.map[point["x"]][point["y"]-1].count() == 0):
				ways.append("up")
		if (point["x"] < (self.size - 1)):
			if (self.map[point["x"]+1][point["y"]].count() == 0):
				ways.append("right")
		if (point["x"] > 0):
			if (self.map[point["x"]-1][point["y"]].count() == 0):
				ways.append("left")
		if (len(ways) == 0):
			ways.append("none")
		myresult = random.choice(ways)
		return myresult

	def randompoint(self, point):
		self.movepoint(point, self.randomside(point))

	def cycle(self):
		for endpoint in self.endpoints[:]:
			x = endpoint["x"]
			y = endpoint["y"]
			if (endpoint["dead"]):
				continue
			if (self.randomside(endpoint) == "none"):
				endpoint["dead"] = True
				continue
			if (random.randint(0,100) < self.branchpercent):
				self.endpoints.append({"x": x, "y": y, "dead": False})
				continue
			if (random.randint(0,100) < self.turnpercent):
				self.randompoint(endpoint)
				continue
			if self.map[x][y].top:
				self.movepoint(endpoint, "down")
			if self.map[x][y].bottom:
				self.movepoint(endpoint, "up")
			if self.map[x][y].left:
				self.movepoint(endpoint, "right")
			if self.map[x][y].right:
				self.movepoint(endpoint, "left")

	def fixCenter(self):
		if (self.map[(self.size//2)][(self.size//2) - 1].bottom == True):
			self.map[self.size//2][self.size//2].top = True
		else:
			self.map[self.size//2][self.size//2].top = False
		if (self.map[(self.size//2) + 1][(self.size//2)].left == True):
			self.map[self.size//2][self.size//2].right = True
		else:
			self.map[self.size//2][self.size//2].right = False
		if (self.map[(self.size//2)][(self.size//2) + 1].top == True):
			self.map[self.size//2][self.size//2].bottom = True
		else:
			self.map[self.size//2][self.size//2].bottom = False
		if (self.map[(self.size//2) - 1][(self.size//2)].right == True):
			self.map[self.size//2][self.size//2].left = True
		else:
			self.map[self.size//2][self.size//2].left = False

	def printNode(self, xvalue=0, yvalue=0):
		return self.map[xvalue][yvalue].left

	def uniBox(self, top=False, right=False, bottom=False, left=False):
		charset = {"top": u"┃",
				   "topright": u"┗",
				   "toprightbottom": u"┣",
				   "toprightbottomleft": u"╋",
				   "toprightleft": u"┻",
				   "topbottomleft": u"┫",
				   "topleft": u"┛",
				   "topbottom": u"┃",
				   "right": u"━",
				   "rightbottom": u"┏",
				   "rightbottomleft": u"┳",
				   "rightleft": u"━",
				   "bottom": u"┃",
				   "bottomleft": u"┓",
				   "left": u"━"}
		indexstring = ""
		if top:
			indexstring = "top"
		if right:
			indexstring = indexstring + "right"
		if bottom:
			indexstring = indexstring + "bottom"
		if left:
			indexstring = indexstring + "left"
		if (indexstring == ""):
			return u"•"
		else:
			return charset[indexstring]


	def printBoard(self):
		print("")
		for j in range(0, self.size):
			printstring = u""
			for i in range(0, self.size):
				printstring = printstring + self.uniBox(self.map[i][j].top, self.map[i][j].right, self.map[i][j].bottom, self.map[i][j].left)
			print(printstring)

	def printHTML(self):
		self.fixCenter()
		printstring = "<table id='gameboard' class='piece'>\n"
		squaresize = "40"
		for j in range(0, self.size):
			printstring += "	<tr>\n"
			for i in range(0, self.size):
				printstring += "		<td id='" + str(i) + "x" + str(j) + "'"
				if (self.map[i][j].top == False):
					if (self.map[i][j].bottom == False):
						if (self.map[i][j].left == False):
							if (self.map[i][j].right == False):
								printstring += " class='blank'"
						if (self.map[i][j].left == True):
							if (self.map[i][j].right == True):
								printstring += " class='line'"
				if (self.map[i][j].top == True):
					if (self.map[i][j].bottom == True):
						if (self.map[i][j].left == False):
							if (self.map[i][j].right == False):
								printstring += " class='line'"
						if (self.map[i][j].left == True):
							if (self.map[i][j].right == True):
								printstring += " class='blank'"
				printstring += ">\n"

				printstring += "		<div class='underlay'></div>\n"

				printstring += "		<img class='TL"
				if (self.map[i][j].top == False):
					printstring += " hideme"
				printstring += "' src='T" + squaresize + ".png'>\n"

				printstring += "		<img class='RL"
				if (self.map[i][j].right == False):
					printstring += " hideme"
				printstring += "' src='R" + squaresize + ".png'>\n"

				printstring += "		<img class='BL"
				if (self.map[i][j].bottom == False):
					printstring += " hideme"
				printstring += "' src='B" + squaresize + ".png'>\n"

				printstring += "		<img class='LL"
				if (self.map[i][j].left == False):
					printstring += " hideme"
				printstring += "' src='L" + squaresize + ".png'>\n"
				printstring += "<div onclick='rotatePiece(this.parentNode); updateTree(centernum); increment();' class='overlay'>.</div>\n"

				printstring += "		</td>\n"

			printstring += "	</tr>\n"
		printstring += "</table>\n\n"
		printstring += "<div id='boardcenter' class='boardinfo'>" + str(self.size//2) + "</div>"
		printstring += "<div id='usedpieces' class='boardinfo'></div>"

		print(printstring)




mytree = Tree(size=int(sys.argv[1]))

for i in range(100):
	mytree.cycle()

mytree.printHTML()









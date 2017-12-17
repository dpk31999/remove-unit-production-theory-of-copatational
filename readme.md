## CSC472 - PROJECT 2
### SIMPLIFICATION OF CONTEXT FREE GRAMMARS

Jose Platas
CSC472

IMPORTANT NOTES:
The program is using the relative path to find the problem1.txt and problem2.text
There shouldnt be any problems running but just in case i attached a "solve_problem_sample.html" file that shows both examples solve

Explanation of how the program works:
- The index.php file reads the files from the example folder
- Once it read the files, it will add them to an associative array.
- Then it will go thought the process of simplifying the grammar
- After is done processing, it will show it on the browser.
- I attached two files to verify that the code is working, one is a screenshot and a html file

<img src="https://raw.githubusercontent.com/joseplatas/csc471_project_2/master/screen_shot_of_solve_problem.png">

PROBLEM
Chapter 6 in the textbook shows that any given context-free grammar can be simplified by removing λ-productions, unit-productions, and useless productions. The algorithms to removing these productions are described in Section 6.1 and were discussed in our classes. In this project, you will implement these algorithms using a programming language.

Requirements:
1.	Design the data structure of representing a context-free grammar.
2.	Choose your programming language.
3.	Implement in one program the three algorithms to removing λ-productions, unit-productions, and useless productions.
4.	Develop test cases and test your implementation. Your test cases should at least include Exercise 7 and Exercise 10 in Section 6.1.

Submission:
1.	The data structure design.
2.	The source code.
3.	The test data (test cases) and test result.

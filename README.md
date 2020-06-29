# Student Fitness Training Session Scheduler
**Date:**  September 2019 - Ongoing

**Organization:** Penn State University

**Project Members:** Myself, and Dave McLaughlin (resident Web Developer at Penn State York)

![](https://github.com/alexkoontz/student-fitness-session-scheduler/blob/master/rdme_src/fitness_site_1.png)

## Problem Description
At Penn State York there was a need for a web application that would allow students to schedule a 30 or 60 minute session with the campus' fitness instructor.  The original way of scheduling was with pen and paper, which was antiquated and made it difficult for students to sign up, cancel, and change the sessions they would like to attend.  The web application would allow the fitness instructor to keep up to date with who has scheduled a session and hopefully improve the turnout of students due to the ease of signing up through a website.

## Brainstorming
Dave McLaughlin initiated the project, and suggested that we create a web application that would allow students to sign in and schedule a session with the fitness trainer.  This website would connect to a database that has days of the week, as well as what sessions are available on specific days.  Students would be able to select a session they would like to attend from a list of available dates and times.  A requirement was that only fitness sessions in the upcoming two weeks would be displayed.  This meant we needed to develop an algorithm to only display a cetain timeframe of sessions.

## Steps

### Phase 1 of Development (September - October 2019)

Dave was able to set up a mySQL database server and an PHP-enabled Apache webserver for us to use.  These were both hosted on a Linux server owned and operated by the University.  Our front-end was setup to utilize the Bootstrap 4 framework.  The bulk of the work for this project was in PHP.  Our initial step was to make sure we could query and modify our database.  After that we integrated the University's WebAccess system, which is a universal login system that allows us to know which student is signed in and keep access to the website exclusive to Penn State students.  Once that was established we wrote some PHP algorithms to display sessions within a date range of two weeks out.  The final front-end design was completed by Dave.  

### Phase 2 of Development (tba)

## Deployment

The campus fitness instructor was briefed on how he would access the database of students that have signed up for fitness sessions.  After we were given approval from him, we were able to get announcements printed from the Student Affairs department annoucing the new website and how students would access it.  We found that a month after deploying the website we had multiple students successfully using it.

## Conclusion
This project allowed me to familiarize myself with the PHP programming language.  It also showed me how to access and modify a database by using a combination of **HTML**, **jQuery**, and **PHP**.  This project used a common network stack know as **LAMP**, which stands for Linux, Apache, MySql, and (in this case) PHP.  This combination of operating system, web server, database manager, and programming language allows for this web application to function properly.  I was also able to think through the algorithm of displaying a certain amount of sessions based on the current day and time.  

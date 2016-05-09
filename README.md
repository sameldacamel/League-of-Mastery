# League of Mastery

Live Site: [LeagueOfMastery.com](http://leagueofmastery.com)

The goal of this project was to deliver a platform that players can go to to battle it out and fight for the Best Champion in their region title! 

This project is written in `HTML/CSS/JavaScript/PHP/Jquery/Angular/Socket.IO/Nodejs`. We utilize a MYSQL database for leaderboard rankings, users, current queues, and current games.

There are 3 main functions to this platform. 

1. First is the login/signup system for summoner to create and compete with their accounts to be displayed on the regional leaderboard. 
2. Second is the tournament platform that creates queues andrecord stats for the champion mastery function. 
3. Lastly, the champion mastery function of this platform is the system that places summoners into the regional leaderboard, match players against players according to champion skill, and the champion points calculator.

Lets dive into how each function of the application works, shall we?

## Login and Signup System

At first we knew, we had to get this sucker out of the way. At the same time we knew we couldn't make some sloppy, unsecure, messy, cheap system. So we decided to go bigger than expected! 

The process starts with loading up the `signup.php` page in your browser. From there you are asked for credentials which are passed onto `signupAuth.js` using POST. Now some may be thinking why didn't we just go straight to the .php file to process this info and sign the user up? Well, we wanted design. By that we mean we didn't want a lot of page loads for the user, so we used Ajax to asynchronously get data from the php file to load into the main view, `signup.php`.

During this process the file `signupAuth.php` is at work making sure usernames and summoners are unique in the MySQL database. After it decides everything is good to go, we get ready to create variables for the user so they are correctly prompted to verify their summoner accounts.

Verification works off of rune page names, so basically we give the user a unique id to set as a rune page name and we update the files to check whether or not they have correctly set their rune page name. Finally, after sign up and verification, players are entered into the lion's den (Not an actually lion's den, just the platform)!

## Tournament Platform

1. So first things first we had to implement a callback check for the site to understand when tournament games were finished. In `classes/callback.php` we start make sure the two players who were supposed to play DID play. If not, then we just threw that game out the window. If so, then we first make a call to the tournament API with our tournament code and matchID to cache the match data. Then, we make sure each player's tournament matchhistory is updated with the latest game. After that, it's simply just going into our database and updating their ELO values.
2. Then the challenge was to get our current queue system to interact with the tournament class to create codes for the players. So we used angular and ajax to make calls to `assets/php/gameSetup.php` to get game info from our database to notify the players with the tournament code.

## Champion Mastery System

So the core purpose of this system is to correctly identify who is best at their champion. Every game affects your ELO no doubt about it. But working with database values and tournaments to make sure that players climb when needed is important. Lets break down each step that goes into this system.

1. We start off by creating a leaderboard table in our database to collect champion level 5s that each player has.
2. Second thing on the list is implementing a link between tournament callbacks and our database to make sure every player has their ELO updated after each game.
3. Last obstacle is displaying the leaderboard rankings, not only on the leaderboard page, but also displaying each player's leaderboard rankings on their summoner page.

> All in all, I had a really fun time working with new technologies such as AngularJS, NodeJS, and Socket.IO. I definatly want to use angular in more web projects just because of its simplicity. GG boys, hope everyone does well :D ~Azoy

####League of Mastery isn't endorsed by Riot Games and doesn't reflect the views or opinions of Riot Games or anyone officially involved in producing or managing League of Legends. League of Legends and Riot Games are trademarks or registered trademarks of Riot Games, Inc. League of Legends © Riot Games, Inc.

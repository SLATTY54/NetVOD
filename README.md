# Developer portal
## Connect to the database
If you when to self-host the database, you have to create a database on the driver you want. You'll have to create a file called `config.ini` where you'll have to put the following:
```ini
driver=
host=
database=
username=
password=
```
and you put this file in the `src` folder. </br>
Your database should have the following tables:
- `EnCours`
- `episode`
- `notation`
- `preferences`
- `serie`
- `User`


## Add feature
If you want to add a new feature, you have to add an Action file. What does this mean, is that you have to create a file named `NameOfTheActionAction` in the folder `src/actions`.
This file need to extend the class `Action` and implement the method `execute`. </br>
After creating your feature, you have to add a line in the switch case of the `src/dispatcher/Dispatcher.php` file. Just follow the same pattern as the others.

## Dashboard of the features
You can access the dashboard by clicking on this link:
<p style="text-align: center;">https://docs.google.com/spreadsheets/d/15o8nag7aDmRHYfqtZ0TLzIt0sOVrhL0Pzrb8kCvORQg/edit?usp=sharing</p>


# User portal
## First time on the website
When you arrive at NetVOD's website, you'll have two options:
- either you have an account, you can log in and start watching videos.
- or you don't have an account, you can create one and start watching videos.

For the creation of your account, you'll have to give us your email address and a password (don't worry, we won't spam you, and your password is encrypted).
Once your account is created, you'll have to activate it by clicking on the link that appears on the page (it's a little red link).

## Want to watch a serie?
If you are log in, you will see the catalog, it's the list of all the series available on NetVOD. You can access a specific serie by clicking on it. You will then see the list of all the episodes available for this serie, and if you access and episode, you will see a video player.

## Cool features
On the home page, in the top right corner, you can see a profile icon. If you click on it, you will access your profile. You can modify a few things:
- your name
- your surname
- your birthdate
- add a biography

There is a system of comments on each episode. To be able to comment you have to access an episode of a serie, you'll see a red button "Donner votre avis" in the top right corner. If you click on it, you'll be able to write a comment and add a note.
You can also see the comments of other users by clicking on the red button "voir les commentaires" on the series' page.

Last cool feature to know about: you can add a serie to your favorites by clicking on the star in the bottom left corner of the catalog page. You'll see the list of your favorites on the homepage.

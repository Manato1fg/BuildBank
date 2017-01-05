# BuildBank
====  
[Japanese](https://github.com/manato0x2cc/buildbank/blob/master/README_ja.md)
## OverView  
BuildBank is an object-oriented world editor plugin for Pocketmine-MP. This plugin outputs your creation as Json file. So you can share your creations each other servers. Also you can move your creations from offline to Pocketmine server. And more, you can name your creations, so you don't have to create same thing.

## Usage  
### To register  
Stand in front of your creation and execute **/bb register**  

You'll see 'Touch the start point' then touch the start point.
(**Important!! You must touch from the front, because BuildBank records the player's direction**)

<img src='https://github.com/Manato0x2cc/BuildBank/raw/master/docs/register_en.png' width=800px height=400px>


After that, touch the end point. (you don't have to care direction)   


At last, enter the name in chat.  
BuildBank'll output json file in **/builds** folder.  

### To copy  
Put the dummy block to left bottom of where you copy. Because if you don't do that, your creation will be filled in the ground.

 (The base is left bottom)  

<img src='https://github.com/Manato0x2cc/BuildBank/raw/master/docs/dummy.png' width=400px height=300px> <br>
Touch the dummy block. Done.  


BuildBank turns the creation automatically, so you don't have to care direction!!  

<img src='https://github.com/Manato0x2cc/BuildBank/raw/master/docs/360.png' width=800px height=400px>


## lang.php
lang.php is CUI Application to adapt other language. Just answer some questions, it makes language file as json. I hope BuildBank supports many languages!!


## To do  
* Add redo.
* make mod. (To move your creations from offline.)
* Make API.

## Licence

[MIT](https://github.com/manato0x2cc/buildbank/blob/master/LICENCE)

## Author

[Manato0x2cc](https://github.com/manato0x2cc)

PHP-Youtube-to-MP3-Converter
============================

Download youtube videos as mp3 with using youtube-mp3.org


EXAMPLE URL : http://www.youtube.com/watch?v=ydRAb9cwHnA<br>
EXAMPLE URL ID : ydRAb9cwHnA<br>

<b>$MP3 	= new youtubemp3("ydRAb9cwHnA");<br>
$MYFILE = $MP3->convert();</b><br>

Optional data ( Never use with download function! )<br>
<b>print_r($MYFILE->fileInfo);</b><br>

<b>$MYFILE->download();</b><br>
tadaaa! Your MP3 file downloaded...<br>
 
You can use this way <br>
<b>$MP3->convert()->download();</b><br>
because this class have a fluent interface.

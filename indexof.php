<?
# IndexOf Enhanced
# By Valerio Capello (Elf Qrin) - http://labs.geody.com/
# r2020-03-31 fr2018-12-29
# License: GPL

# die(); # die unconditionately, locking out any access

# if ($_GET['pwd']!='123'.'45') {die('unauthorized');} # Simple password protection

/*
# direct DNS based ban (blacklist)
switch($_SERVER['REMOTE_HOST']) {
case 'host-192-0-2-0.example.com':
case 'host-192-0-2-1.example.net':
die();
break;
default:
break;
}

# direct IP based ban (blacklist)
switch($_SERVER['REMOTE_ADDR']) {
case '192.0.2.0':
case '192.0.2.1':
die();
break;
default:
break;
}

# direct DNS based ban (whitelist)
switch($_SERVER['REMOTE_HOST']) {
case 'host-192-0-2-0.example.com':
case 'host-192-0-2-1.example.net':
break;
default:
die();
break;
}

# direct IP based ban (whitelist)
switch($_SERVER['REMOTE_ADDR']) {
case '192.0.2.0':
case '192.0.2.1':
break;
default:
die();
break;
}
*/


# Lang

$es_indexof='Index of';
$es_dircur='Current Directory';
$es_dirpar='Parent Directory';
$es_dirempornf='Directory empty or not found';
$es_num='N.';
$es_name='Name';
$es_ext='Ext';
$es_typ='Type';
$es_lastmod='Last modified';
$es_size='Size';
# $es_desc='Description';
$es_total='Total';
$es_element='element';
$es_elements='elements';
$es_file='file';
$es_files='files';
$es_dir='dir';
$es_dirs='dirs';
$es_hidd='hidden';
$es_runnphp='running PHP';
$es_on='on';
$es_at='at';
$es_port='Port';
$es_using='using';
$es_date='Date';
$es_user='User';


# Config

$sself=$_SERVER['PHP_SELF']; // This line should normally be left untouched
$requri=$_SERVER['REQUEST_URI']; // This line should normally be left untouched
$rn=strlen($requri); $r=strrpos($requri,'/'); if ($r!==false && $r<$rn) {$reqpath=substr($requri,0,$r+1);} else {$reqpath='/';} // This line should normally be left untouched
$dir1=getcwd().'/'; if ($dir1==='//') {$dir1='/';} // This line should normally be left untouched

$robotsinst='noindex,nofollow,noarchive'; # Directives Robots (Website Crawlers)

// $titleh=$es_indexof.' '.$reqpath; # Title in Header
$titleh=$es_indexof.' '.rtrim($reqpath,'/'); # Title in Header (trim trailing slash)
$titlep=$titleh; # Title in Page

$frevsort=false; # Reverse file order

$fnamalt=array('/^\.$/' => $es_dircur, '/^\.\.$/' => $es_dirpar); # Alternate Names (RegEx)

$enlink=true; # Enable link for files
$linkparams=''; # $linkparams='target="_blank"'; # Parameters for links
$linknoext=false; # Hide extensions

$enaudioplay=false; # Enable Audio Player for mp3 and ogg files

$linkexc=array('.',basename($_SERVER['PHP_SELF']),'index.html','index.htm','index.php'); # Exclude file names
$xlistext=array('php'); # Extension Block List (Blacklist/Whitelist)
$xlistextmod=0; # Extension Block List Mode: 0: Ignore, 1: Blacklist, 2: Whitelist
$xlisttyp=array('IMG','SND'); # File Type Block List (Blacklist/Whitelist). In Upper Case
$xlisttypmod=0; # File Type Block List Mode: 0: Ignore, 1: Blacklist, 2: Whitelist

$finfo=array('bullet'=>false, 'cnt'=>false, 'ftyptxt'=>false, 'ftypimg'=>true, 'fname'=>true, 'fext'=>true, 'size'=>true, 'datemu'=>false, 'dateml'=>true); # File Info: Bullet (Unnumbered List), Counter (Numbered List), File Type (txt), File Type (img, requires Apache Webserver images), File Name, File Extension, File Size, Last Modified Date (UTC), Last Modified Date (Server Local).

$bulletchar='&#8226;'; # Bullet (shown if $finfo['bullet'] == true )
$icopath='/icons/'; # Path where icons are stored (normally /icons/ on Apache Webserver; shown if $finfo['ftypimg'] == true)
$shwfthdr=true; # Show File Table Header
$shwdir=true; # Show Directories
$shwfil=true; # Show Files
$fsfmt=2; # File Size format: 1: bytes, 2: human readable;
$tshwtot=true; # Show Totals
$shwtot=array('num'=>true, 'numfildir'=>true, 'hidden'=>false, 'size'=>true); # Show Totals: Total Number, Number of Files and Directories, Number of Hidden Elements, Total Size.

$fnamelimlen=false; # Set Max Length of the File Name
$fnamelimlenmx=32; # Max Length of the File Name (if $fnamelimlen == true)
$fnamelimlenov='...'; # String to append if File Name is longer than allowed length

$fextlimlen=false; # Set Max Length of the File Extension
$fextlimlenmx=8; # Max Length of the File Extension (if $fnamelimlen == true)
$fextlimlenov='...'; # String to append if File Extension is longer than allowed length

$allowusort=true; # Allow user to sort fields
$sortdir=1; # Show Directories: 0: Between files (don't sort), 1: First (Top), 2: Last (Bottom)

$tserver=false; # Return Information about the Server
$tsts=array('host'=>true, 'ip'=>true, 'port'=>true, 'os'=>true, 'webserversoft'=>true, 'php'=>true); # Server: Show Host Name, IP address, Port, OS, Web Server Software, PHP.

$tstsd=array('dateu'=>false, 'datel'=>true); # Show Date (UTC), Date (Local)

$tclient=false; # Return Information about the Client
$tstc=array('ip'=>true, 'port'=>true, 'os'=>true, 'browser'=>true, 'uagent'=>true); # Client: Show IP address, Port, User Agent. Note that the User Agent may be forged.


# Functions

function hrsize($bytes=0,$base=1024) {
$si_prefix=array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
$class=min((int)log($bytes , $base),count($si_prefix)-1);
return sprintf('%1.2f',$bytes/pow($base,$class)).' '.$si_prefix[$class];
}

function switchVal($v0, $v1, $v2, $cs=true) {
$r=$v0;
if ($cs) {$vc0=$v0; $vc1=$v1; $vc2=$v2;} else {$vc0=strtolower($v0); $vc1=strtolower($v1); $vc2=strtolower($v2);}
if ($vc0===$vc1) {$r=$v2;} elseif ($vc0===$vc2) {$r=$v1;}
return $r; 
}

// Requires PHP 5.3 or higher
function sortArray($dat, $fld, $nat=true, $o=0) {
if (strtolower(trim($o))=='a') {$o=0;}
if (!is_array($fld)) {$fld = array($fld);}
usort($dat, function($a, $b) use($fld) {
$retval = 0;
foreach($fld as $fldname) {
if ($retval == 0) {
if ($nat) {
$retval = strnatcmp($a[$fldname],$b[$fldname]);
} else {
$retval = strcmp($a[$fldname],$b[$fldname]);
}
}
}
return $retval;
});
if ($o===1 || $o==='d' || $o==='D') {$dat=array_reverse($dat,false);}
return $dat;
}

function getRemoteOS($user_agent) {
$os_platform = "Unknown";
$os_array = array('/windows nt 10/i' => 'Windows 10', '/windows nt 6.3/i' => 'Windows 8.1', '/windows nt 6.2/i' => 'Windows 8', '/windows nt 6.1/i' => 'Windows 7', '/windows nt 6.0/i' => 'Windows Vista', '/windows nt 5.2/i' => 'Windows Server 2003/XP x64', '/windows nt 5.1/i' => 'Windows XP', '/windows xp/i' => 'Windows XP', '/windows nt 5.0/i' => 'Windows 2000', '/windows me/i' => 'Windows ME', '/win98/i' => 'Windows 98', '/win95/i' => 'Windows 95', '/win16/i' => 'Windows 3.11', '/macintosh|mac os x/i' => 'Mac OS X', '/mac_powerpc/i' => 'Mac OS 9', '/linux/i' => 'Linux', '/ubuntu/i' => 'Ubuntu', '/iphone/i' => 'iPhone', '/ipod/i' => 'iPod', '/ipad/i' => 'iPad', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'webOS');
foreach ($os_array as $regex => $value) {
if (preg_match($regex, $user_agent)) {
$os_platform = $value; break;
}
} 
return $os_platform;
}

function getRemoteBrowser($user_agent) {
$browser = "Unknown";
$browser_array = array('/msie/i' => 'Internet Explorer', '/firefox/i' => 'Firefox', '/safari/i' => 'Safari', '/chrome/i' => 'Chrome', '/edge/i' => 'Edge', '/opera/i' => 'Opera', '/netscape/i' => 'Netscape', '/maxthon/i' => 'Maxthon', '/konqueror/i' => 'Konqueror', '/lynx/i' => 'Lynx', '/wget/i' => 'Wget');
foreach ($browser_array as $regex => $value) {
if (preg_match($regex, $user_agent)) {
$browser = $value; break;
}
}
if (preg_match('/mobile/i', $user_agent)) {$browser.=' ('.'Mobile'.')';}
return $browser;
}


$filesindir1=scandir($dir1); $nfilesindir1=count($filesindir1);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="Robots" content="<? echo $robotsinst; ?>" />
<title><? echo $titleh; ?></title>
<style type="text/css">
/* Light Theme */
/*
body {background-color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #111111; margin-left: 10px; margin-top: 10px; margin-right: 10px; margin-bottom: 10px;}
a {font-style: inherit; font-size: inherit; color: inherit; text-decoration: inherit;}
a:link {color: #0000ee;}
a:visited {color: #0000dd;}
a:hover {color: #3232fe;}
a:active {color: #ee1111;}
.tfil {}
.tdir {background-color: #ffce75}
tr.trh {}
tr.trf {}
tr.trf:hover {background-color: #faf9c0;}
*/


/* Dark Theme */

body {background-color: #111111; font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #efefef; margin-left: 10px; margin-top: 10px; margin-right: 10px; margin-bottom: 10px;}
a {font-style: inherit; font-size: inherit; color: inherit; text-decoration: inherit;}
a:link {color: #3f90bf;}
a:visited {color: #2f7fae;}
a:hover {color: #50b2d0;}
a:active {color: #ee1111;}
.tfil {}
.tdir {background-color: #602011}
tr.trh {}
tr.trf {}
tr.trf:hover {background-color: #204105;}

/* More */

.tbullet {}
.tcnt {}
.tcntt {}
.tfext {}
.ttyptxt {font-family: monospace; font-size: 90%;}
.ttypimg {font-family: monospace; font-size: 90%;}
.itypimg {vertical-align: middle; margin-top: 0px; margin-bottom: 0px; margin-left: 1px; margin-right: 1px;}
.tfftyptxt {font-family: monospace; font-size: 90%;}
.tfsiz {font-family: monospace; font-size: 90%;}
.tfdat {font-family: monospace; font-size: 90%;}

.ttot {}
.tsrv {}
.tcli {}
.tdat {}

.dtabl1 {}
.tabl1 {border-spacing: 2px; border-collapse: separate; border: 0px;}
.tablth1 {padding: 2px; font-weight: bold;}
.tabltd1 {padding: 2px;}

.imgthumb {vertical-align: middle; text-align: center; height: 100px; width: auto; max-width: 200px; margin-top: 1px; margin-bottom: 1px; margin-left: auto; margin-right: auto; border: 0px solid #000000; display: block;}

</style>
</head>
<body>
<h1><? echo $titlep; ?></h1>
<?
if (!is_readable($dir1)) {echo $es_dirempornf.'.'; die();}
if (is_file($dir1)) {echo $es_dirempornf.'.'; die();}

if ($allowusort) {
$dirsortc=strtolower(trim($_REQUEST['C'])); $dirsorto=strtolower(trim($_REQUEST['O']));
if (($dirsortc!='n' && $dirsortc!='m' && $dirsortc!='s' && $dirsortc!='e' && $dirsortc!='t') || (($dirsortc=='n' && !$finfo['fname']) || ($dirsortc=='m' && !$finfo['dateml']) || ($dirsortc=='s' && !$finfo['size']) || ($dirsortc=='e' && !$finfo['fext']) || ($dirsortc=='t' && !$finfo['ftyptxt']) )) {$dirsortc='';}
if ($dirsorto!='d') {$dirsorto='a';}
} else {$dirsortc=''; $dirsorto='a';}

if ($nfilesindir1) {
$dir=array();
$nelt=0; $nel=0; $neld=0; $nelf=0; $tfsz=0;
for ($i=0;$i<$nfilesindir1;++$i) {
++$nelt;
$cfile=$filesindir1[$i]; $iscfiledir=is_dir($dir1.$cfile);
if (($iscfiledir && !$shwdir) || (!$iscfiledir && !$shwfil)) {continue;}
if (!in_array($cfile,$linkexc)) {
$ofttxt=''; $oftico=''; $cfsz=0;

if ($iscfiledir) {$fexto=''; $fext='';} else {$fexto=pathinfo($cfile, PATHINFO_EXTENSION); $fext=strtolower($fexto);}

if ($finfo['ftyptxt'] || $finfo['ftypimg'] || $xlisttypmod>0) {
if ($iscfiledir) {
if ($cfile=='..') { $ofttxt='DIR'; $oftico='up.gif';  } else { $ofttxt='DIR'; $oftico='folder.gif'; }
} else {
if ($fext=='bin') {$ofttxt='BIN'; $oftico='binary.gif';}
elseif ($fext=='exe' || $fext=='com' || $fext=='msi' || $fext=='bat' || $fext=='pif') {$ofttxt='EXE'; $oftico='binary.gif';}
elseif ($fext=='7z' || $fext=='7zip' || $fext=='arc' || $fext=='arj' || $fext=='bhx' || $fext=='zip' || $fext=='z' || $fext=='gz' || $fext=='gzip' || $fext=='hqx' || $fext=='sit' || $fext=='stuffit' || $fext=='mim' || $fext=='tar' || $fext=='taz' || $fext=='tgz' || $fext=='rar' || $fext=='bz' || $fext=='bz2' || $fext=='bzip' || $fext=='bzip2' || $fext=='tbz' || $fext=='tbz2' || $fext=='xxe' || $fext=='ace' || $fext=='lha' || $fext=='lzh' || $fext=='arc' || $fext=='zoo' || $fext=='mim' || $fext=='mime' || $fext=='b64' || $fext=='yenc' || $fext=='ync' || $fext=='ntx' || $fext=='ear' || $fext=='war' || $fext=='qwk' || $fext=='pk3' || $fext=='rep' || $fext=='cab' || $fext=='cpio' || $fext=='deb' || $fext=='rpm' || $fext=='jar' || $fext=='wsz' || $fext=='wal' || $fext=='000' || $fext=='001' || $fext=='r00' || $fext=='r01' || $fext=='par' || $fext=='p00' || $fext=='p01' || $fext=='txtz' || $fext=='textz' || $fext=='htmz' || $fext=='htmlz') {$ofttxt='ARC'; $oftico='compressed.gif';}
elseif ($fext=='uu' || $fext=='uue') {$ofttxt='ARC'; $oftico='uuencoded.gif';}
elseif ($fext=='pae' || $fext=='pgp' || $fext=='gpg' || $fext=='pgd' || $fext=='tc' || $fext=='tcd' || $fext=='key' || $fext=='pcx' || $fext=='kdb' || $fext=='edc') {$ofttxt='ENC'; $oftico='binary.gif';}
elseif ($fext=='aac' || $fext=='ac3' || $fext=='ape' || $fext=='cda' || $fext=='dts' || $fext=='flac' || $fext=='fla' || $fext=='mid' || $fext=='midi' || $fext=='mod' || $fext=='xm' || $fext=='s3m' || $fext=='it' || $fext=='mka' || $fext=='mp1' || $fext=='mp2' || $fext=='mp3' || $fext=='mpa' || $fext=='mpc' || $fext=='ofr' || $fext=='ogg' || $fext=='pls' || $fext=='ra' || $fext=='wav' || $fext=='voc' || $fext=='au' || $fext=='aif' || $fext=='aiff' || $fext=='wma') {$ofttxt='AUD'; $oftico='sound1.gif';}
elseif ($fext=='3gp' || $fext=='3gpp' || $fext=='albw' || $fext=='asf' || $fext=='asx' || $fext=='avi' || $fext=='avs' || $fext=='b4s' || $fext=='dat' || $fext=='dvx' || $fext=='divx' || $fext=='flv' || $fext=='ifo' || $fext=='m1v' || $fext=='m2v' || $fext=='m3v' || $fext=='m4v' || $fext=='m3u' || $fext=='mkv' || $fext=='mov' || $fext=='mp4' || $fext=='mpg' || $fext=='mpe' || $fext=='mpeg' || $fext=='mpv' || $fext=='nsv' || $fext=='ogm' || $fext=='part' || $fext=='rm' || $fext=='ram' || $fext=='rmvb' || $fext=='rmvbr' || $fext=='rv' || $fext=='vob' || $fext=='ts' || $fext=='vp6' || $fext=='wm' || $fext=='wmv' || $fext=='wpl' || $fext=='xvid' || $fext=='xvi' || $fext=='zpl') {$ofttxt='VID'; $oftico='movie.gif';}
elseif ($fext=='jpg' || $fext=='jpeg' || $fext=='jpe' || $fext=='jif' || $fext=='jfif' || $fext=='jp2' || $fext=='gif' || $fext=='png' || $fext=='bmp' || $fext=='dib' || $fext=='rle' || $fext=='kdc' || $fext=='pdc' || $fext=='pcx' || $fext=='dcx' || $fext=='pic' || $fext=='pix' || $fext=='tga' || $fext=='tif' || $fext=='tiff' || $fext=='iff' || $fext=='lbm' || $fext=='ilbm' || $fext=='art' || $fext=='sgi' || $fext=='bw' || $fext=='rgb' || $fext=='rgba' || $fext=='cpt' || $fext=='psd' || $fext=='xpm' || $fext=='ico' || $fext=='icon' || $fext=='raw') {$ofttxt='IMG'; $oftico='image2.gif';}
elseif ($fext=='ai' || $fext=='cdr' || $fext=='svg' || $fext=='ps' || $fext=='eps' || $fext=='emf' || $fext=='wmf' || $fext=='odg') {$ofttxt='VEC'; $oftico='image1.gif';}
elseif ($fext=='dwg' || $fext=='dxf' || $fext=='csf' || $fext=='3ds' || $fext=='max' || $fext=='dae' || $fext=='abc' || $fext=='fbx' || $fext=='ply' || $fext=='obj' || $fext=='x3d' || $fext=='stl' || $fext=='3mf' || $fext=='vrml' || $fext=='vrm') {$ofttxt='3DV'; $oftico='sphere1.gif';}
elseif ($fext=='dbf' || $fext=='mdb' || $fext=='mdw' || $fext=='db1' || $fext=='db2' || $fext=='db3' || $fext=='sql' || $fext=='sqlite' || $fext=='odb') {$ofttxt='DBS'; $oftico='binary.gif';}
elseif ($fext=='xls' || $fext=='xlsx' || $fext=='xlw' || $fext=='wk1' || $fext=='wk2' || $fext=='wk3' || $fext=='wk4' || $fext=='wq1' || $fext=='xla' || $fext=='slk' || $fext=='dif' || $fext=='csv' || $fext=='ods') {$ofttxt='SHT'; $oftico='layout.gif';}
elseif ($fext=='ppt' || $fext=='pptx' || $fext=='ppa' || $fext=='pps' || $fext=='ppsx' || $fext=='pot' || $fext=='odp') {$ofttxt='PRS'; $oftico='pie1.gif';}
elseif ($fext=='doc' || $fext=='docx' || $fext=='dot' || $fext=='mcw' || $fext=='ws' || $fext=='ws4' || $fext=='ws7' || $fext=='wps' || $fext=='vor' || $fext=='abw' || $fext=='ans' || $fext=='rtf' || $fext=='odt' || $fext=='sxw' || $fext=='sxc' || $fext=='sxi' || $fext=='odt' || $fext=='odp' || $fext=='ott') {$ofttxt='DOC'; $oftico='layout.gif';}
elseif ($fext=='pdf') {$ofttxt='EBK'; $oftico='pdf.gif';}
elseif ($fext=='epub' || $fext=='mobi' || $fext=='azw3' || $fext=='djvu' || $fext=='fb2' || $fext=='lit' || $fext=='lrf' || $fext=='pdb' || $fext=='rb' || $fext=='snb' || $fext=='tcr') {$ofttxt='EBK'; $oftico='layout.gif';}
elseif ($fext=='txt' || $fext=='text' || $fext=='asc' || $fext=='prn' || $fext=='prt' || $fext=='diz' || $fext=='inf' || $fext=='info' || $fext=='me' || $fext=='1st' || $fext=='first') {$ofttxt='TXT'; $oftico='text.gif';}
elseif ($fext=='xml') {$ofttxt='XML'; $oftico='xml.png';}
elseif ($fext=='htm' || $fext=='html' || $fext=='shtml' || $fext=='xhtml') {$ofttxt='WEB'; $oftico='text.gif';}
elseif ($fext=='eml' || $fext=='msg' || $fext=='msb' || $fext=='mhl' || $fext=='dbx' || $fext=='tbb' || $fext=='tbi') {$ofttxt='EML'; $oftico='text.gif';}
elseif ($fext=='sh' || $fext=='js' || $fext=='vb' || $fext=='vba' || $fext=='bas' || $fext=='pas' || $fext=='c' || $fext=='h' || $fext=='cpp' || $fext=='vcproj' || $fext=='cs' || $fext=='py' || $fext=='rb' || $fext=='go' || $fext=='asm' || $fext=='arm' || $fext=='php' || $fext=='php3' || $fext=='php4' || $fext=='inc' || $fext=='cgi' || $fext=='pl' || $fext=='r' || $fext=='xpi') {$ofttxt='COD'; $oftico='script.gif';}
elseif ($fext=='conf' || $fext=='config' || $fext=='cfg' || $fext=='ini' || $fext=='local' || $fext=='prf' || $fext=='pref' || $fext=='prefs' || $fext=='prof' || $fext=='profile') {$ofttxt='CFG'; $oftico='text.gif';}
elseif ($fext=='iso' || $fext=='cue' || $fext=='nrg' || $fext=='nri' || $fext=='nr3' || $fext=='nrw' || $fext=='nr4' || $fext=='nra' || $fext=='nrm' || $fext=='nre' || $fext=='nrv' || $fext=='nsd' || $fext=='nrd' || $fext=='nmd' || $fext=='nhv' || $fext=='nmb' || $fext=='nrs' || $fext=='nrh' || $fext=='nru' || $fext=='nrc' || $fext=='nrg' || $fext=='nhf') {$ofttxt='BMG'; $oftico='binary.gif';}
elseif ($fext=='kml' || $fext=='kmz' || $fext=='gpx' || $fext=='ov2' || $fext=='itn' || $fext=='mps') {$ofttxt='GPS'; $oftico='world2.gif';}
elseif ($fext=='2li') {$ofttxt='SAT'; $oftico='world1.gif';}
elseif ($fext=='odf') {$ofttxt='MAT'; $oftico='binary.gif';}
elseif ($fext=='exe' || $fext=='com' || $fext=='msi' || $fext=='bat' || $fext=='pif') {$ofttxt='EXE'; $oftico='binary.gif';}
elseif ($fext=='bak' || $fext=='bup') {$ofttxt='BAK'; $oftico='binary.gif';}
elseif ($fext=='tmp' || $fext=='t' || $fext=='temp' || $fext=='$$$') {$ofttxt='TMP'; $oftico='binary.gif';}
else {$ofttxt='???'; $oftico='unknown.gif';}
}
} else {$ofttxt=''; $oftico='';}

$cfnam=$cfile; $procfn=true;
foreach ($fnamalt as $regex => $value) {
if (preg_match($regex, $cfile)) {
$cfnam=$value; $procfn=false; break;
}
}

if ($xlistextmod>0) {
if (($xlistextmod==1 && in_array($fext,$xlistext)) || ($xlistextmod==2 && !in_array($fext,$xlistext))) {continue;}
}

if ($xlisttypmod>0) {
if (($xlisttypmod==1 && in_array($ofttxt,$xlisttyp)) || ($xlisttypmod==2 && !in_array($ofttxt,$xlisttyp))) {continue;}
}

++$nel; if ($iscfiledir) {++$neld;} else {++$nelf;}

$cfsz=filesize($dir1.$cfile);
$tfsz+=$cfsz;

if ($sortdir==0 || $sortdir==1) {
if ($iscfiledir) {$isdirsort=0;} else {$isdirsort=1;}
} else {
if ($iscfiledir) {$isdirsort=1;} else {$isdirsort=0;}
}
if ($sortdir==2 && $dirsorto==='d') {$isdirsort=switchVal($isdirsort,0,1,true);}

$dir[]=array('isdir'=>$iscfiledir, 'isdirsort'=>$isdirsort, 'fext'=>$fexto, 'ofttxt'=>$ofttxt, 'oftico'=>$oftico, 'file'=>$cfile, 'fnam'=>$cfnam, 'fsz'=>str_pad($cfsz, 18, '0', STR_PAD_LEFT), 'fmdat'=>filemtime($dir1.$cfile));
}
}


// Sort allowed fields, either user selected or from the config
$dirsrtflds=array();
if ($sortdir) {$dirsrtflds[]='isdirsort';}
switch ($dirsortc) {
case 'n':
$dirsrtflds[]='fnam';
break;
case 'm':
$dirsrtflds[]='fmdat';
break;
case 's':
$dirsrtflds[]='fsz';
break;
case 'e':
$dirsrtflds[]='fext';
break;
case 't':
$dirsrtflds[]='ofttxt';
break;
case '':
default:
$dirsrtflds[]='file';
break;
}
$dir=sortArray($dir,$dirsrtflds,true,$dirsorto);


echo '<div class="dtabl1">';
echo '<table class="tabl1">';
if ($shwfthdr) {
echo '<tr class="trh">';
if ($finfo['bullet']) {echo '<th class="tablth1">'.''.'</th>';}
if ($finfo['cnt']) {echo '<th class="tablth1">'.$es_num.'</th>';}

if ($finfo['ftyptxt']) {echo '<th class="tablth1">'; if ($allowusort) { if ($dirsortc=='t') {$dirsortcth=switchVal($dirsorto,'A','D',false);} else {$dirsortcth='A';}; echo '<a href="'.$sself.'?C=T&O='.strtoupper($dirsortcth).'">'.$es_typ.'</a>';} else {echo $es_typ;}; echo '</th>';}
if ($finfo['ftypimg']) {echo '<th class="tablth1">'.''.'</th>';}
if ($finfo['fname']) {echo '<th class="tablth1">'; if ($allowusort) { if ($dirsortc=='n' || $dirsortc=='') {$dirsortcth=switchVal($dirsorto,'A','D',false);} else {$dirsortcth='A';}; echo '<a href="'.$sself.'?C=N&O='.strtoupper($dirsortcth).'">'.$es_name.'</a>';} else {echo $es_name;}; echo '</th>';}
if ($finfo['fext']) {echo '<th class="tablth1">'; if ($allowusort) { if ($dirsortc=='e') {$dirsortcth=switchVal($dirsorto,'A','D',false);} else {$dirsortcth='A';}; echo '<a href="'.$sself.'?C=E&O='.strtoupper($dirsortcth).'">'.$es_ext.'</a>';} else {echo $es_ext;}; echo '</th>';}
// if ($enaudioplay) {echo '<th class="tablth1">'.'<img src="'.$icopath.'sound1.gif'.'" border="0" alt="" />'.'</th>';}
// echo '<th class="tablth1">'.'<img src="'.$icopath.'image2.gif'.'" border="0" alt="" />'.'</th>';
if ($finfo['size']) {echo '<th class="tablth1">'; if ($allowusort) { if ($dirsortc=='s') {$dirsortcth=switchVal($dirsorto,'A','D',false);} else {$dirsortcth='A';}; echo '<a href="'.$sself.'?C=S&O='.strtoupper($dirsortcth).'">'.$es_size.'</a>';} else {echo $es_size;}; echo '</th>';}
if ($finfo['datemu']) {echo '<th class="tablth1">'; if ($allowusort) { if ($dirsortc=='m') {$dirsortcth=switchVal($dirsorto,'A','D',false);} else {$dirsortcth='A';}; echo '<a href="'.$sself.'?C=M&O='.strtoupper($dirsortcth).'">'.$es_lastmod.' '.'('.'UTC'.')'.'</a>';} else {echo $es_lastmod.' '.'('.'UTC'.')';}; echo '</th>';}
if ($finfo['dateml']) {echo '<th class="tablth1">'; if ($allowusort) { if ($dirsortc=='m') {$dirsortcth=switchVal($dirsorto,'A','D',false);} else {$dirsortcth='A';}; echo '<a href="'.$sself.'?C=M&O='.strtoupper($dirsortcth).'">'.$es_lastmod.'</a>';} else {echo $es_lastmod;}; echo '</th>';}
echo '</tr>';
}

for ($i=0;$i<count($dir);++$i) {
$cfile=$dir[$i]['file']; $iscfiledir=$dir[$i]['isdir'];
if (($iscfiledir && !$shwdir) || (!$iscfiledir && !$shwfil)) {continue;}
$fexto=$dir[$i]['fext']; $fext=strtolower($fexto); $ofttxt=$dir[$i]['ofttxt']; $oftico=$dir[$i]['oftico']; $cfile=$dir[$i]['file']; $cfnam=$dir[$i]['fnam']; $cfsz=($dir[$i]['fsz'])+0; $fmdat=$dir[$i]['fmdat'];

echo '<tr class="trf">';
if ($finfo['bullet']) {echo '<td class="tabltd1">'.'<div class="tbullet">'.$bulletchar.'</div>'.'</td>';}
if ($finfo['cnt']) {echo '<td class="tabltd1">'.'<div class="tcnt">'.($i+1).'</div>'.'</td>';}

if ($finfo['ftyptxt']) {
echo '<td class="tabltd1">'.'<div class="ttyptxt">';
echo '['.$ofttxt.']';
echo '</div>'.'</td>';
}
if ($finfo['ftypimg']) {
echo '<td class="tabltd1">'.'<div class="ttypimg">';
echo '<img src="'.$icopath.$oftico.'" class="itypimg" border="0" alt="'.'['.$ofttxt.']'.'" />';
echo '</div>'.'</td>';
}

if ($finfo['fname']) {
$cfnam=$cfile; $procfn=true;
foreach ($fnamalt as $regex => $value) {
if (preg_match($regex, $cfile)) {
$cfnam=$value; $procfn=false; break;
}
}

if ($procfn) {
if ($linknoext) {$r=strrpos($cfile,'.'); if ($r!==false && $r>0) {$cfnam=substr($cfile,0,$r);}}
}
if ($fnamelimlen && strlen($cfnam)>$fnamelimlenmx) {$cfnam=substr($cfnam,0,$fnamelimlenmx).$fnamelimlenov;}
echo '<td class="tabltd1">';
if ($enlink) {echo '<a href="'.$cfile.'" '.$linkparams.'>';}
echo '<div class="';
if ($iscfiledir) {echo 'tdir';} else {echo 'tfil';}
echo '">';
echo $cfnam;
echo '</div>';
if ($enlink) {echo '</a>';}
if ($enaudioplay) {
if ($fext=='ogg') { echo ' <audio controls><source src="'.$cfile.'" type="audio/ogg"></audio>'; }
if ($fext=='mp3') { echo ' <audio controls><source src="'.$cfile.'" type="audio/mpeg"></audio>'; }
}
echo '</td>';
}

if ($finfo['fext']) {
echo '<td class="tabltd1">'.'<div class="tfext">';
if ($fextlimlen && strlen($fexto)>$fextlimlenmx) {echo substr($fexto,0,$fextlimlenmx).$fextlimlenov;} else {echo $fexto;}
echo '</div>'.'</td>';
}

/*
if ($enaudioplay) {
echo '<td class="tabltd1">';
if ($fext=='ogg') { echo '<audio controls><source src="'.$cfile.'" type="audio/ogg"></audio>'; }
if ($fext=='mp3') { echo '<audio controls><source src="'.$cfile.'" type="audio/mpeg"></audio>'; }
echo '</td>';
}
*/
/*
echo '<td class="tabltd1">';
if ($fext=='gif' || $fext=='png' || $fext=='jpg' || $fext=='jpeg') { echo '<img src="'.$cfile.'" class="imgthumb" />'; }
echo '</td>';
*/

if ($finfo['size']) {
echo '<td class="tabltd1">'.'<div class="tfsiz">';
if (!$iscfiledir) {
if ($fsfmt==1) {
echo $cfsz; // echo ' '.'('.hrsize($cfsz).')';
} else {
echo hrsize($cfsz); // echo ' '.'('.$cfsz.')';
}
} else {
echo '-';
}
echo '</div>'.'</td>';
}
if ($finfo['datemu']) {echo '<td class="tabltd1">'.'<div class="tfdat">'.gmdate("D d-M-Y H:i:s",$fmdat).' '.'UTC'.'</div>'.'</td>';}
if ($finfo['dateml']) {echo '<td class="tabltd1">'.'<div class="tfdat">'.date("D d-M-Y H:i:s",$fmdat).'</div>'.'</td>';}
echo '</tr>';
}


echo '</table>';
echo '</div>';
} else {
echo $es_dirempornf.'.';
}

echo "<br />";

if ($tshwtot && ($shwtot['num'] || $shwtot['numfildir'] || $shwtot['hidden'] || $shwtot['size'])) {
echo '<div class="ttot">';
echo $es_total.': ';
if ($shwtot['num']) {echo $nel.' '; if ($nel==1) {echo $es_element;} else {echo $es_elements;}}
if ($shwtot['numfildir']) {
echo ' '.'(';
echo $nelf.' '; if ($nelf==1) {echo $es_file;} else {echo $es_files;}
echo ', '.$neld.' '; if ($neld==1) {echo $es_dir;} else {echo $es_dirs;}
if ($shwtot['hidden']) {echo '; '.($nelt-$nel).' '.$es_hidd;}
echo ')';
} elseif ($shwtot['hidden']) {echo ' '.'('.($nelt-$nel).' '.$es_hidd.')';}
if ($shwtot['size']) {
if ($shwtot['num'] || $shwtot['numfildir'] || $shwtot['hidden']) {echo ', ';}
if ($fsfmt==1) {
echo $tfsz.' '.'B';
} else {
echo hrsize($tfsz);
}
}
echo '</div>';
}

if ($tserver || $tclient || $tstsd['dateu'] || $tstsd['datel']) {echo '<hr />';}

if ($tserver) {
echo '<div class="tsrv">';
if ($tsts['webserversoft']) {echo $_SERVER['SERVER_SOFTWARE'].' '.'Server'." ";}
if ($tsts['php']) {echo $es_runnphp.' '.PHP_VERSION." ";}
if ($tsts['os']) {echo $es_on.' '.php_uname('s').' '.php_uname('v').' ('.php_uname('m').')'." ";}
if ($tsts['host']) {echo $es_at.' '.$_SERVER['HTTP_HOST']." ";}
if ($tsts['ip']) {echo '('.$_SERVER['SERVER_ADDR'].')'." ";}
if ($tsts['port']) {echo $es_port.' '.$_SERVER['SERVER_PORT'];}
echo '</div>';
}

if ($tclient) {
echo '<div class="tsrv">';
echo $es_user.': ';
if ($tstc['ip']) {echo $_SERVER['REMOTE_ADDR']." ";}
if ($tstc['port']) {echo $es_port.' '.$_SERVER['REMOTE_PORT']." ";}
if ($tstc['browser']) {echo $es_using.' '.getRemoteBrowser(addslashes($_SERVER['HTTP_USER_AGENT']))." ";}
if ($tstc['os']) {echo $es_on.' '.getRemoteOS(addslashes($_SERVER['HTTP_USER_AGENT']))." ";}
if ($tstc['uagent']) {echo '('.addslashes($_SERVER['HTTP_USER_AGENT']).')';}
echo '</div>';
}

if ($tstsd['dateu']) {echo '<div class="tdat">'.$es_date.': '.gmdate('D d-M-Y H:i:s').' '.'UTC'.'</div>';}
if ($tstsd['datel']) {
$ntzl=date('Z'); if ($ntzl>0) {$ntzsl="+";} else {$ntzsl="";}
echo '<div class="tdat">'.$es_date.': '.date('D d-M-Y H:i:s').' '.'UTC'.$ntzsl.($ntzl/3600).'</div>';
}

?>
</body>
</html>
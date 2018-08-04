 <?php
session_start();						
?>
<!DOCTYPE html>
<!-- http://localhost/customv3/index.php?scadfile=example.scad -->

<html>
<head>
<meta charset="utf-8" />
<title>Mikaël 3-D Customizer !!!</title>
<?php
//gt
echo exec('TERM= xterm');
require $_SERVER['DOCUMENT_ROOT'].'/customv3/vendor/autoload.php';
//@ require dirname(__FILE__).'/vendor/autoload.php';
?>
</head>
<link href="https://fonts.googleapis.com/css?family=Bungee+Shade" rel="stylesheet">
<style>
h1 {
font-family: 'Bungee Shade', cursive;
}
</style>

<body>
  <script src="js/three.js"></script>
  <script src="js/loaders/STLLoader.js"></script>
  <script src="js/controls/OrbitControls.js"></script>
<script type="text/javascript">
function showValue(newValue,elemId)
{
	document.getElementById(elemId).innerHTML=newValue;
}
</script>
<table cellpadding="10">
<tr>
<td>
<table cellpadding="10">
<tr>
<td align="right"><img src="./image/logoMR.png" height="107"></td>
<td align="left" valign="top"><h1>Mikaël <br>3-D Customizer</h1></td>
</tr>
</table>
<table>
<tr>
<td align="left" valign="top">File: <?php echo $_GET['scadfile'] ?></td>
<td></td>
</tr>
</table>
<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	//@ echo "Beginning of file...<br>";
	
	$scadfile = $_SERVER['DOCUMENT_ROOT'].'/customv3/start/'.$_GET['scadfile'];
	
	//@ echo "Got $scadfile ...<br>";
	
	$render3d = new \Libre3d\Render3d\Customizer();
	
	
	//@  echo "Successful initiated class Render3d...<br>";
	
	// this is the working directory, where it will put any files used during the render process, as well as the final
	// rendered image.
	//@ workingDir need to have 777 permission.
	 $render3d->workingDir($_SERVER['DOCUMENT_ROOT'].'/customv3/files/');
//	$render3d->workingDir('/home/emilien/most-3-d-customizer/files/'); 
	//@ $render3d->workingDir(dirname(__FILE__).'/files/');
	
	//@  echo "Successful assigned working directory...<br>";
	
	// Set paths to the executables on this system
	$render3d->executable('openscad', '/usr/bin/openscad');
	
	//@ echo "Successful executable openscad...<br>";
	
	$render3d->executable('povray', '/usr/bin/povray');
	
	//@  echo "Successful executable povray...<br>";
	
	try {
		// This will copy in your starting file into the working DIR if you give the full path to the starting file.
		// This will also set the fileType for you.
		$render3d->filename($scadfile);
		//@ $render3d->filename(dirname(__FILE__).'/start/example.stl');
	
		// Render!  This will do all the necessary conversions as long as the render engine (in this
		// case, the default engine, PovRAY) "knows" how to convert the file into a file it can use for rendering.
		// Note that this is a multi-step process that can be further broken down if you need it to.
		$renderedImagePath = $render3d->render('povray');
	$stlobject= str_replace("png","stl",$renderedImagePath);
	$stlobject2 = str_replace($_SERVER['DOCUMENT_ROOT'],'',$stlobject);
debug_print_backtrace();
	//echo($stlobject2);
		//@ echo "Render successful!  Rendered image will be at $renderedImagePath <br>";
	?>
	
	
	
	<table cellpadding="10">
	<tr>
	<td valign="top" HEIGHT="10" WIDTH="350">
	
	<span id= "childElement">souris bouton gauche = rotation / souris bouton droit = translation molette = zoom </span>
	<script>
var container, camera, scene, renderer;

init();
animate();

function init(){
    container=document.createElement('div');
	var sp2 = document.getElementById("childElement");
	var parentDiv = sp2.parentNode;
	
    parentDiv.insertBefore(container,sp2);

    

    scene=new THREE.Scene();
	var WIDTH = 450,
          HEIGHT = 400;
		  
		
		  
camera=new THREE.PerspectiveCamera(35, WIDTH / HEIGHT, 1, 10000);
    camera.position.set(5, -50, 200);

    // object
    var loader=new THREE.STLLoader();
    loader.load(<?php echo json_encode($stlobject2); ?>, function (geometry){
       // var geometry=event.content;
        var material=new THREE.MeshLambertMaterial({ emissive: 0x000a30,color: 0xfdd017 });
        var mesh=new THREE.Mesh(geometry, material);
        scene.add(mesh);});

	var grid = new THREE.GridHelper( 500, 500, 0xffffff, 0x555555 );
				grid.rotateOnAxis( new THREE.Vector3( 1, 0, 0 ), 90 * ( Math.PI/180 ) );
				scene.add( grid );

				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				document.body.appendChild( renderer.domElement );


    // lights
    scene.add(new THREE.AmbientLight(0x736F6E));
	scene.background = new THREE.Color( 0x999999 );
    var directionalLight=new THREE.DirectionalLight(0xffffff, 1);
    directionalLight.position=camera.position;
    scene.add(directionalLight);
	// seconde light
	var light2 = new THREE.DirectionalLight(0xffffff,1);
      light2.position.set(0,-1000,500);
      scene.add(light2);
	 // var light3 = new THREE.DirectionalLight(0x88ff77,1);
     // light3.position.set(0,1000,500);
     // scene.add(light3);

    // renderer

    renderer=new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(WIDTH, HEIGHT);

    container.appendChild(renderer.domElement);

    window.addEventListener('resize', onWindowResize, false);
	
	// Add OrbitControls so that we can pan around with the mouse.
      controls = new THREE.OrbitControls(camera, renderer.domElement);
	
	}

function addLight(x, y, z, color, intensity){
    var directionalLight=new THREE.DirectionalLight(color, intensity);
    directionalLight.position.set(x, y, z)
    scene.add(directionalLight);}

function onWindowResize(){
    camera.aspect= WIDTH / HEIGHT;
    camera.updateProjectionMatrix();
    renderer.setSize(WIDTH, HEIGHT);}

  function animate(){
           // Read more about requestAnimationFrame at http://www.paulirish.com/2011/requestanimationframe-for-smart-animating/
      requestAnimationFrame(animate);
      
      // Render the scene.
      renderer.render(scene, camera);
      controls.update();
 }

  function render(){
      var timer=Date.now() * 0.0005;
      r=150;
      camera.position.x=r*Math.cos(timer);
      camera.position.z=r*Math.sin(timer);
      camera.lookAt(scene.position);
      renderer.render(scene, camera);
      renderer.setClearColor(0xf5f5f5, 1);}
</script>
	
	</td>
	<?php
	} catch (\Exception $e) {
		echo "Render failed :( Exception: ".$e->getMessage()."<br>";
	}
	//@ folder start need to have 777 permission
	$result = $render3d->readSCAD($scadfile);
	if(empty($result)) {
		echo "Something is wrong with the file!";
	}
	else {
		//@ echo "The SCAD file is customizable!<br>";
		//$_SESSION['parameters'] = array();
		$_SESSION['parameters'] = $result;
		//@ serialize class object before it can be stored in session
		$_SESSION['render3d'] = serialize($render3d);
		//$storeClass = = serialize($render3d);
		//file_put_contents('storeClass',$storeClass);
		$_SESSION['scadfile'] = $scadfile;
		//@ count how many rows
		$rows = count($result);
?>
		<td valign="top">
		<form name="submitForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
<?php
		//@ 0:description, 1:variable name, 2: value, 3:possible value
		for($i=0;$i<$rows;$i++) {
			//@ echo $result[$i][0]." " .$result[$i][1]." ". $result[$i][2]." ". $result[$i][3]."<br>";
			$element = $render3d->generateElement($result[$i],$i);
			echo $element;
			$element ='';
		}

?>
			<input type="submit" id="submit" value="Save">
		</form>
		</td></tr></table>
		</td>
		</tr>
		</table>
<?php
	}
	
	//@ echo "End of file...<br>";
}
else {
	//@ Save button was clicked
	//@ echo "Save was clicked!<br>";
	/* if(empty($_SESSION['parameters'])) {
		echo "Session is empty!<br>";
	}
	else {
		echo "Session is not empty!<br>";
	}*/
	//$result = array();
	$result = $_SESSION['parameters'];
	//@ count how many rows
	$rows = count($result);
	//@ echo "There are ". $rows . " rows!<br>";
	//@ update value of each parameter
	//@ 0:description, 1:variable name, 2: value, 3:possible value
	for($i=0;$i<$rows;$i++) {
		//mod
		//if ($result[$i][0] !=''){
		$result[$i][2] = htmlspecialchars($_POST['var'.$i]);
		// echo $result[$i][0] ." " . $result[$i][1] ." ". $result[$i][2] ." ". $result[$i][3] ."<br>";
		//}
		//else{
		//	$i+=1;
		//}
	}

	//@ update parameters to a new scad file
	//@ unserialize class object to variable
	$render3d = unserialize($_SESSION['render3d']);
	//$storeClass = file_get_contents('storeClass');
	//$render3d = unserialize($storeClass);
	$scadfile = $_SESSION['scadfile'];
	//@ echo "original scad file ".$scadfile." ...<br>";
	$newscadname = $render3d->writeSCAD($scadfile, $result);
	//@ echo "Successfully created new SCAD file ".$newscadname." !<br>";
	//@ reload itself with new scasd filename
	header('Location:'.$_SERVER['PHP_SELF'].'?scadfile='.$newscadname);
	exit;
}
?>

</body>
</html>

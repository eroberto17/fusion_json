<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="bootstrap.min.css">
<style type="text/css">
		.response{
			border: 1px solid #1a1a1a;
			margin-top: 10px;
			padding: 10px 5px;
			font-family: monospace;
			background: #1a1a1a;
			color: white;
			white-space: pre-wrap;
			text-align: left;
			border-radius: 3px;
		}

	</style>

</head>
<body>

<div class="container" style="margin-top:5%">


<div class="form-group">
	<label>Project name</label>
	<input class="form-control" type="text" name="project_name" id="project_name" placeholder="Project name, Example: XXX-XXX" autofocus="autofocus">
	<br>
	<label>Aplication name</label>
	<input class="form-control" type="text" name="app_name" id="app_name" placeholder="Aplication name, Example: XXX">
	<br>

	<label>Repository</label>
	<div class="row">
    <div class="col-xs-6 form-inline">
			<label>https://github.com/</label> <input onkeyup="mirrorValue(this.value)" class="form-control" type="text" name="repository" id="repository" placeholder="First value" style="width: 60%;">
		</div>
    <div class="col-xs-6 form-inline">
			<label>/</label> <input class="form-control" type="text" name="repository_second" id="repository_second" placeholder="Second value" style="width: 60%;">
		</div>
	</div>
	<br>
	<label>Repository manifiesto</label>
	<div class="row">
    <div class="col-xs-6 form-inline">
			<label>https://github.com/</label> <input class="form-control" type="text" name="repository_manifiesto" id="repository_manifiesto" placeholder="First value" style="width: 60%;">
		</div>
    <div class="col-xs-6 form-inline">
			<label>/</label> <input class="form-control" type="text" name="repository_manifiesto_second" id="repository_manifiesto_second" placeholder="Second value" style="width: 60%;">
		</div>
	</div>


	<br>
	<label>Json File</label>
	<input class="form-control" type="file" name="json_file" id="json_file" accept="application/json">
</div>


<button class="btn btn-info" id="btn_check" onclick="showHint()">Check Data</button>
<br>
<button class="btn btn-success" id="btn_download" onclick="download()" style="visibility:hidden; margin-top:-50px;">Download</button>

<br>
<br>

<label>Data Preview</label>
<br>
<span id="txtHint"></span>

<div class="response"></div>


</div>


<script>

function mirrorValue(str){
	var repository = document.getElementById("repository").value;
	document.getElementById("repository_manifiesto").value = repository;
}


function showHint(str) {

	var project_name = document.getElementById("project_name").value;
	var app_name = document.getElementById("app_name").value;
	var repository = document.getElementById("repository").value;
	var repository_second = document.getElementById("repository_second").value;
	var repository_manifiesto = document.getElementById("repository_manifiesto").value;
	var repository_manifiesto_second = document.getElementById("repository_manifiesto_second").value;
	var file = document.getElementById("json_file").files;

  if (project_name.length == 0 || app_name.length == 0) {
    document.querySelector(".response").textContent = "Por favor, complete Project name, Aplication name";
    document.getElementById("project_name").focus();
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var text = JSON.parse(this.responseText);
        document.querySelector(".response").textContent = JSON.stringify(text, null, 4);
        document.getElementById('btn_download').style.visibility = 'visible';
        document.getElementById('btn_check').style.visibility = 'hidden';
        document.getElementById("project_name").disabled=true;
				document.getElementById("app_name").disabled=true;
				document.getElementById("repository").disabled=true;
				document.getElementById("repository_second").disabled=true;
				document.getElementById("repository_manifiesto").disabled=true;
				document.getElementById("repository_manifiesto_second").disabled=true;
				document.getElementById("json_file").disabled=true;
      }
    }

    var formData = new FormData();
		formData.append("file", file[0]);

    xmlhttp.open("POST", "check.php?project_name="+project_name+"&app_name="+app_name+"&repository="+repository+"&repository_second="+repository_second+"&repository_manifiesto="+repository_manifiesto+"&repository_manifiesto_second="+repository_manifiesto_second, true);
    xmlhttp.send(formData);
  }
}

function download(){
	document.getElementById('btn_download').style.visibility = 'hidden';
	var project_name = document.getElementById("project_name").value;
	var app_name = document.getElementById("app_name").value;
	var repository = document.getElementById("repository").value;
	var repository_second = document.getElementById("repository_second").value;
	var repository_manifiesto = document.getElementById("repository_manifiesto").value;
	var repository_manifiesto_second = document.getElementById("repository_manifiesto_second").value;
	var file = document.getElementById("json_file").files;
	
	if (project_name.length == 0 || app_name.length == 0) {
    document.querySelector(".response").textContent = "Por favor, complete Project name, Aplication name y seleccione un archivo JSON válido";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var text = JSON.parse(this.responseText);
        //document.querySelector(".response").textContent = "Archivo json-file.json descargado";
        download_file(JSON.stringify(text, null, 4), project_name+"-"+app_name+".json", "text/plain");
        location.reload();
      }
    }

    var formData = new FormData();
		formData.append("file", file[0]);

    xmlhttp.open("POST", "process.php?project_name="+project_name+"&app_name="+app_name+"&repository="+repository+"&repository_second="+repository_second+"&repository_manifiesto="+repository_manifiesto+"&repository_manifiesto_second="+repository_manifiesto_second, true);
    xmlhttp.send(formData);
  }
}


function download_file(content, fileName, contentType) {
	const a = document.createElement("a");
	const file = new Blob([content], { type: contentType });
	a.href = URL.createObjectURL(file);
	a.download = fileName;
	a.click();
}

</script>

</body>
</html>
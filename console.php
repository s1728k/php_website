<!DOCTYPE html>
<html>
<head>
	<title>Console</title>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<h3 class="text-center">Console Commands</h3><br>
				<table class="table">
					<thead>
						<tr>
							<th style="width:30%">Command</th>
							<th style="width:10%">Button</th>
							<th style="width:60%">Remark</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Database Connection Test</td>
							<td><button class="btn btn-primary" style="width:100%" onclick="connectionTest()">Test</button></td>
							<td id="dbt"></td>
						</tr>
						<tr>
							<td>Database Create</td>
							<td><button class="btn btn-primary" style="width:100%" onclick="createDatabase()">Create</button></td>
							<td id="dc"></td>
						</tr>
						<tr>
							<td>Database Migrate</td>
							<td><button class="btn btn-primary" style="width:100%" onclick="migrate()">Migrate</button></td>
							<td id="dm"></td>
						</tr>
						<tr>
							<td>Database Rollback</td>
							<td><button class="btn btn-primary" style="width:100%" onclick="rollback()">Rollback</button></td>
							<td id="dr"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
		function connectionTest(){
			$.post('/database/connection.php', {},function(data){
					$("#dbt").html(data);
			})
		}
		function createDatabase(){
			$.post('/database/create_database.php', {},function(data){
					$("#dc").html(data);
			})
		}
		function migrate(){
			$.post('/database/migrate.php', {},function(data){
					$("#dm").html(data);
			})
		}
		function rollback(){
			$.post('/database/rollback.php', {},function(data){
					$("#dr").html(data);
			})
		}
	</script>
</body>
</html>
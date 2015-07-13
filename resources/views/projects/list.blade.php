<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>
</head>
<body>
<div class="container">
    <div class="content">
        <ul>
            @foreach($projects as $project)
                <li>ID:{{$project->id}} Name:{{$project->name}}</li>
            @endforeach
        </ul>
    </div>
</div>
</body>
</html>

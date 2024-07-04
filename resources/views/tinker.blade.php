<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tinker Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-5">

    <h1 class="text-3xl font-bold">Tinker Web</h1>

    <form method="post">
        @csrf
        <div class="py-2">
            <label class="">
                Command
                <br>
                <textarea rows="7" name="command" class="block p-2 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $command }}</textarea>
            </label>

            <button type="submit" class="my-2 inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">Execute</button>
        </div>
    </form>

    <div>
        @if($command)
            @php
                try {
                    echo "<pre>";
                    eval($command);
                    echo "</pre>";
                } catch (\Throwable $e) {
                    echo "<div>";
                    echo $e->getMessage();
                    echo "</div>";
                }
            @endphp
        @endif
    </div>
</body>
</html>

{{-- resources/views/project-discussion.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Discussion</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-4xl w-full bg-white shadow-2xl rounded-3xl overflow-hidden">

        {{-- Header --}}
        <div class="bg-indigo-600 text-white p-6">
            <h1 class="text-3xl font-bold">DeliverEats Project Review</h1>
            <p class="opacity-80 mt-2">
                Team discussion with our “legendary” TA 😂
            </p>
        </div>

        {{-- Content --}}
        <div class="grid md:grid-cols-2 gap-8 p-8 items-center">

            {{-- Meme Image Section --}}
            <div class="relative">
                {{-- Replace with your own meme image path --}}
                <img
                    src="{{ asset('images/engSoliman.jpg') }}"
                    alt="Funny TA"
                    class="rounded-2xl shadow-xl border-4 border-indigo-200"
                >

                <div class="absolute bottom-4 left-4 bg-black/70 text-white px-4 py-2 rounded-xl text-sm">
                    “Why is the API returning 500 again?”
                </div>
            </div>

            {{-- Chat Style Discussion --}}
            <div class="space-y-4">

                <div class="bg-gray-100 p-4 rounded-2xl shadow-sm">
                    <p class="font-semibold text-indigo-600">TA:</p>
                    <p>
                        “Did you test edge cases?”
                    </p>
                </div>

                <div class="bg-indigo-100 p-4 rounded-2xl shadow-sm">
                    <p class="font-semibold text-indigo-700">Team:</p>
                    <p>
                        “We tested in production 😭”
                    </p>
                </div>

                <div class="bg-gray-100 p-4 rounded-2xl shadow-sm">
                    <p class="font-semibold text-indigo-600">TA:</p>
                    <p>
                        “Who pushed directly to main?”
                    </p>
                </div>

                <div class="bg-red-100 p-4 rounded-2xl shadow-sm">
                    <p class="font-semibold text-red-700">Git History:</p>
                    <p>
                        Kareem — 47 force pushes detected 💀
                    </p>
                </div>

                <button
                    class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 transition text-white py-3 rounded-2xl font-semibold shadow-lg"
                >
                    Deploy Anyway 🚀
                </button>

            </div>

        </div>
    </div>

</body>
</html>
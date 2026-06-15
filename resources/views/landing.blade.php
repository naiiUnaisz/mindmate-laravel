<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mindmate</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-[#F9F9FF] text-slate-800 antialiased overflow-x-hidden">
    <div class="absolute -top-20 -left-20 w-[450px] h-[450px] sm:w-[550px] sm:h-[550px] bg-[#6C5CE7]/15 rounded-full blur-[100px] pointer-events-none z-0"></div>

    <header class="sticky top-0 z-50 w-full bg-[#F9F9FF]/95 backdrop-blur-sm border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between relative">

            <div class="flex items-center gap-4 z-50">
                <div class="w-10 h-10 flex items-center justify-center rounded-xl overflow-hidden">
                    <img src="{{ asset('images/Screenshot_2026-05-28_at_11.14.51-removebg-preview.png') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="font-bold tracking-wide text-xl text-[#2D2D2D]">Mindmate</span>
            </div>

            <input type="checkbox" id="menu-toggle" class="peer hidden">

            <nav class="hidden peer-checked:flex md:flex absolute md:absolute top-full md:top-1/2 left-0 md:left-1/2 w-full md:w-auto bg-white md:bg-transparent shadow-xl md:shadow-none p-6 md:p-0 flex-col md:flex-row items-center gap-4 md:gap-12 text-sm font-medium text-black transition-all duration-300 ease-in-out box-border z-40 md:-translate-x-1/2 md:-translate-y-1/2">

                <!-- Feature -->
                <a href="#" class="hover:text-[#6C5CE7] py-2 md:py-0 w-full md:w-auto text-center font-semibold transition">Feature</a>

                <!-- How It Works -->
                <a href="#how-it-works" class="hover:text-[#6C5CE7] transition py-2 md:py-0 w-full md:w-auto text-center font-semibold">How It Works</a>

                <!-- Preview (Sudah digeser agak ke kanan) -->
                <a href="#preview" class="hover:text-[#6C5CE7] transition py-2 md:py-0 w-full md:w-auto text-center font-semibold md:transform md:translate-x-4">Preview</a>

            </nav>

            <div class="hidden md:flex items-center gap-4 text-sm font-medium">
                <a href="javascript:void(0)" onclick="showDownloadModal()" class="bg-[#6C5CE7] text-white py-2.5 px-5 rounded-xl hover:bg-[#5b4cc4] transition shadow-sm shadow-purple-200">Download</a>

            </div>

            <label for="menu-toggle" class="md:hidden flex flex-col gap-1.5 cursor-pointer p-2 text-slate-800 z-50 select-none">
                <span class="w-6 h-0.5 bg-slate-800 transition-all duration-300"></span>
                <span class="w-6 h-0.5 bg-slate-800 transition-all duration-300"></span>
                <span class="w-6 h-0.5 bg-slate-800 transition-all duration-300"></span>
            </label>

        </div>
    </header>

    <section class="max-w-7xl mx-auto px-6 pt-8 md:pt-16 pb-16 md:pb-24 flex flex-col md:flex-row items-center gap-10 w-full box-border relative">


        <div class="w-full md:w-1/2 text-center md:text-left order-1 relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-4xl font-bold text-[#111111] leading-tight mb-4">
                Turn Your Tasks Into <br class="hidden sm:block">
                <span class="text-black">Progress You Can See</span>
            </h1>
            <p class="text-black text-sm sm:text-base mb-6 max-w-sm sm:max-w-md mx-auto md:mx-0 leading-relaxed">
                Complete your daily tasks and stay <br> consistent so you can see how far <br> you have come.
            </p>

            <button onclick="showDownloadModal()" class="bg-[#6C5CE7] hover:bg-[#5b4cc4] text-white font-medium px-7 py-3.5 rounded-2xl shadow-lg shadow-[#6C5CE7]/20 transition-all flex items-center gap-3 text-base group">
                <span>Download</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 transition-transform duration-200 group-hover:translate-y-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-3.5-3.5M12 15l3.5-3.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5h15" />
                </svg>
            </button>

        </div>

        <div class="w-full md:w-1/2 flex justify-center items-center order-2 px-4 mt-4 md:mt-0 relative z-10">
            <div class="absolute w-[400px] h-[400px] sm:w-[550px] sm:h-[550px] bg-[#6C5CE7]/15 rounded-full blur-[100px] pointer-events-none z-0 translate-x-16 translate-y-12"></div>
            <img src="{{ asset('images/Screenshot_2026-05-28_at_11.02.36-removebg-preview.png') }}"
                alt="Hero Image"
                class="w-full max-w-[250px] sm:max-w-[300px] md:max-w-full h-auto object-contain">
        </div>
    </section>

    <section id="feature" class="max-w-7xl mx-auto px-6 mb-24">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <div class="flex items-start gap-2 py-0.5 pl-0 pr-0 rounded-xl cursor-pointer transition-all duration-300 ease-in-out hover:scale-105 hover:-translate-y-1 hover:shadow-xl hover:bg-slate-50/50">
                <div class="w-14 h-14 shrink-0 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/fuzzle.png') }}" alt="Puzzle Progress" class="w-full h-full object-contain">
                </div>
                <div class="space-y-0.5 pt-0.5">
                    <h4 class="font-semibold-400 text-lg text-slate-900 tracking-tight">Puzzle Progress</h4>
                    <p class="text-[11px] font-medium  text-black  leading-tight max-w-[150px]]">
                        Complete tasks and <br>collect puzzle pieces <br> every day.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-2 py-0.5 pl-0 pr-0 rounded-xl cursor-pointer transition-all duration-300 ease-in-out hover:scale-105 hover:-translate-y-1 hover:shadow-xl hover:bg-slate-50/50">
                <div class="w-14 h-14 shrink-0 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/senyum.png') }}" alt="Mood Tracking" class="w-full h-full object-contain">
                </div>
                <div class="space-y-0.5 pt-0.5">
                    <h4 class="font-semibold-400 text-lg text-slate-900 tracking-tight">Mood Tracking</h4>
                    <p class="text-[11px] font-medium  text-black leading-tight max-w-[150px]">
                        Track your mood in <br>seconds with simple <br> emoji.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-2 py-0.5 pl-0 pr-0 rounded-xl cursor-pointer transition-all duration-300 ease-in-out hover:scale-105 hover:-translate-y-1 hover:shadow-xl hover:bg-slate-50/50">
                <div class="w-14 h-14 shrink-0 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/gatay.png') }}" alt="Weekly Insights" class="w-full h-full object-contain">
                </div>
                <div class="space-y-0.5 pt-0.5">
                    <h4 class="font-semibold-400 text-lg text-slate-900 tracking-tight">Weekly Insights</h4>
                    <p class="text-[11px] font-medium  text-black  leading-tight max-w-[150px]">
                        See your mood and <br> productivity patterns <br> over time.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-2 py-0.5 pl-0 pr-0 rounded-xl cursor-pointer transition-all duration-300 ease-in-out hover:scale-105 hover:-translate-y-1 hover:shadow-xl hover:bg-slate-50/50">
                <div class="w-14 h-14 shrink-0 rounded-full flex items-center justify-center">
                    <img src="{{ asset('images/api.png') }}" alt="Streak & Rewards" class="w-full h-full object-contain">
                </div>
                <div class="space-y-0.5 pt-0.5">
                    <h4 class="font-semibold-400 text-lg text-slate-900 tracking-tight">Streak & Rewards</h4>
                    <p class="text-[11px] font-medium text-black leading-tight max-w-[150px">
                        Build streaks, unlock <br> badges, and stay <br>motivated!
                    </p>
                </div>
            </div>

        </div>
    </section>
    <section id="how-it-works" class=" scroll-mt-24 py-12 max-w-7xl mx-auto px-6 text-center mb-24">
        <span class="inline-block mb-6 px-4 py-1.5 bg-[#F0EBFF] text-[#6338E8] text-xs font-bold rounded-full uppercase tracking-wider">How It Works</span>
        <h2 class="text-2xl md:text-3xl font-bold text-[#2D2D2D] mt-4 mb-16">Simple steps for a better you</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative max-w-4xl mx-auto">
            <div class="flex flex-col items-center">
                <div class="w-28 h-28 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden p-3">
                    <img src="{{ asset('images/buku.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm"><span class="text-purple-600 font-bold mr-1">1</span> Plan Your Day</h3>
                <p class="text-xs text-black max-w-xs leading-relaxed">Add your tasks for the day <br> and get ready to achieve <br>your goals.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-28 h-28 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden p-3">
                    <img src="{{ asset('images/pager.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm"><span class="text-yellow-500 font-bold mr-1">2</span> Complete & Collect</h3>
                <p class="text-xs text-black max-w-xs leading-relaxed">Complete your tasks <br>and collect puzzle pieces <br> as a reward.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-28 h-28 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden p-3">
                    <img src="{{ asset('images/duit.png')}} " alt="" class="w-full h-full object-contain">
                </div>
                <h3 class="font-semibold text-slate-800 mb-2 text-sm"><span class="text-red-500 font-bold mr-1">3</span> Track & Improve</h3>
                <p class="text-xs  text-black max-w-xs leading-relaxed">Track your mood, see <br> insights, and become a <br>better version of yourself.</p>
            </div>
        </div>
    </section>

    <section id="preview" class=" scroll-mt-24 py-12 max-w-7xl mx-auto px-6 text-center mb-24">
        <span class="inline-block mb-6 px-4 py-1.5 bg-[#F0EBFF] text-[#6338E8] text-xs font-bold rounded-full uppercase tracking-wider">App Preview</span>
        <h2 class="text-2xl md:text-3xl font-bold text-[#2D2D2D] mt-4 mb-12">Designed to make productivity enjoyable</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
            <div class="bg-[#ECEBFF] rounded-3xl p-6 flex flex-col justify-between min-h-[380px] md:h-[450px] overflow-hidden shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:rotate-1 cursor-pointer">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 mb-2">Daily Puzzle</h3>
                    <p class="text-xs text-black leading-relaxed max-w-[200px]">Complete tasks to <br> reveal your beautiful <br> puzzle piece by piece.</p>
                </div>
                <div class="mt-6 bg-white rounded-2xl p-4 shadow-sm translate-y-4">
                    <img src="{{ asset('images/nyenye.png') }}" alt="Puzzle UI" class="w-full h-auto rounded-xl">
                </div>
            </div>
            <div class="bg-[#FFFCE4] rounded-3xl p-6 flex flex-col justify-between min-h-[380px] md:h-[450px] overflow-hidden shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:rotate-1 cursor-pointer">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 mb-2">Weekly History</h3>
                    <p class="text-xs text-black leading-relaxed max-w-[200px]">See your mood and <br>task completion at a <br> glance.</p>
                </div>
                <div class="mt-6 bg-white rounded-2xl p-4 shadow-sm translate-y-4">
                    <img src="{{ asset('images/mood.png') }}" alt="History UI" class="w-full h-auto rounded-xl">
                </div>
            </div>
            <div class="bg-[#FFEBEB] rounded-3xl p-6 flex flex-col justify-between min-h-[380px] md:h-[450px] overflow-hidden shadow-sm transition-all duration-300 ease-in-out hover:scale-105 hover:-translate-y-2 hover:shadow-2xl hover:rotate-1 cursor-pointer">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 mb-2">Coins & Rewards</h3>
                    <p class="text-xs text-black leading-relaxed max-w-[200px]">Get insights about <br> your habits and mood <br> patterns.</p>
                </div>
                <div class="mt-6 flex gap-2 translate-y-4">
                    <div class="bg-white rounded-2xl shadow-sm w-1/2">
                        <img src="{{ asset('images/detail.png') }}" alt="Coins UI" class="w-full h-auto rounded-lg">
                    </div>
                    <div class="bg-white rounded-2xl p-3 shadow-sm w-1/2">
                        <img src="{{ asset('images/relax.png') }}" alt="Store UI" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="max-w-7xl mx-auto px-6 mb-24">
        <h2 class="text-2xl md:text-3xl font-bold text-center text-[#2D2D2D] mb-12">Review from User</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-slate-200 rounded-full overflow-hidden shrink-0">
                            <img src="{{ asset('images/evan.jpeg') }}" alt="Evan" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-slate-800 leading-tight">Evan</h4>
                            <span class="text-xs text-slate-400">Student</span>
                        </div>
                    </div>
                    <div class="flex gap-0.5 mb-4 text-yellow-400 text-base">⭐⭐⭐⭐⭐</div>
                    <p class="text-sm font-medium text-black leading-relaxed">"This app somehow makes productivity feel less stressful 😂 I keep opening it just to check my streak and end up finishing my tasks too."</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-slate-200 rounded-full overflow-hidden shrink-0">
                            <img src="{{ asset('images/karina.jpeg') }}" alt="Karin" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-slate-800 leading-tight">Karinn</h4>
                            <span class="text-xs text-slate-400">Influencer</span>
                        </div>
                    </div>
                    <div class="flex gap-0.5 mb-4 text-yellow-400 text-base">⭐⭐⭐⭐⭐</div>
                    <p class="text-sm font-medium text-black leading-relaxed">"The design is actually sooooo pretty. The little mascot and reward system make the app feel alive instead of boring ✨"</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-slate-200 rounded-full overflow-hidden shrink-0">
                            <img src="{{ asset('images/nicho.jpeg') }}" alt="Nicho" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-slate-800 leading-tight">Nicho</h4>
                            <span class="text-xs text-slate-400">Dancer</span>
                        </div>
                    </div>
                    <div class="flex gap-0.5 mb-4 text-yellow-400 text-base">⭐⭐⭐⭐⭐</div>
                    <p class="text-sm font-medium text-black leading-relaxed">"I've tried so many productivity apps before, but this is the first one that didn't make me feel pressured all the time 🌙"</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-slate-200 rounded-full overflow-hidden shrink-0">
                            <img src="{{ asset('images/reiy.jpeg') }}" alt="Reiy" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-base text-slate-800 leading-tight">Reiy</h4>
                            <span class="text-xs text-slate-400">Freelancer Designer</span>
                        </div>
                    </div>
                    <div class="flex gap-0.5 mb-4 text-yellow-400 text-base">⭐⭐⭐⭐⭐</div>
                    <p class="text-sm font-medium text-black leading-relaxed">"Really cute and motivating app. I just wish there were more customization options for the themes and mascot 👀"</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-[#6a5fac] text-white pt-12 pb-6 px-6 md:px-20 text-sm">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-12 border-b border-white/10 pb-8">
            <div class="max-w-xs">
                <div class="flex items-center gap-2 mb-4">
                    <img src="{{ asset('images/footerr.png') }}" alt="Mindmate" class="h-6">
                    <h2 class="text-lg font-bold tracking-wide">Mindmate</h2>
                </div>
                <p class="text-white leading-relaxed">Productivity and self-care app that helps users stay focused, organized, and balanced through daily tasks, puzzle rewards, mood tracking, and coin-based entertainment features.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-12 md:gap-20 w-full md:w-auto">
                <div>
                    <h3 class="font-bold tracking-wide mb-4 md:mb-6 text-white text-base">Application</h3>
                    <ul class="space-y-3 text-white">
                        <li><a href="#" class="hover:underline hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:underline hover:text-white transition">Preview</a></li>
                        <li><a href="#" class="hover:underline hover:text-white transition">About Mindmate</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold tracking-wide mb-4 md:mb-6 text-white text-base">Resources</h3>
                    <ul class="space-y-3 text-white">
                        <li><a href="#" class="hover:underline hover:text-white transition">Contact Us</a></li>
                        <li><a href="#" class="hover:underline hover:text-white transition">Press Kit</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold tracking-wide mb-4 md:mb-6 text-white text-base">Download</h3>
                    <ul class="space-y-3 text-white">
                        <li><a href="javascript:void(0)" onclick="showDownloadModal()" class="hover:underline hover:text-white transition">Download APK</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </footer>

<div id="downloadModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[999] hidden">
    <div class="bg-white rounded-3xl p-8 max-w-sm mx-4 shadow-2xl text-center">
        <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-yellow-100">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-yellow-500"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">Download MindMate</h3>
        <p class="text-sm text-slate-500 mb-6 leading-relaxed">
            This app is not available on the Google Play Store. You are about to download an APK file from an unofficial source. Make sure to enable <strong>"Install from unknown sources"</strong> in your device settings.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDownloadModal()" class="flex-1 py-3 border border-slate-200 rounded-xl text-slate-600 font-medium hover:bg-slate-50 transition">Cancel</button>
            <a href="{{ asset('apk-mindmate/mindmate.apk') }}" download="MindMate.apk" onclick="closeDownloadModal()" class="flex-1 py-3 bg-[#6C5CE7] text-white rounded-xl font-medium hover:bg-[#5b4cc4] transition text-center">Download</a>
        </div>
    </div>
</div>

<script>
function showDownloadModal() {
    document.getElementById('downloadModal').classList.remove('hidden');
}
function closeDownloadModal() {
    document.getElementById('downloadModal').classList.add('hidden');
}
</script>

</body>

</html>
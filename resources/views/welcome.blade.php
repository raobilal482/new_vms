
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>VMS</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
     
    <link rel="canonical" href="https://https://demo.themesberg.com/landwind/" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS</title>

    <!-- Meta SEO -->
    <meta name="title" content="Landwind - Tailwind CSS Landing Page">
    <meta name="description" content="Get started with a free and open-source landing page built with Tailwind CSS and the Flowbite component library.">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Themesberg">

    <!-- Social media share -->
    <meta property="og:title" content=Landwind - Tailwind CSS Landing Page>
    <meta property="og:site_name" content=Themesberg>
    <meta property="og:url" content=https://https://demo.themesberg.com/landwind/>
    <meta property="og:description" content=Get started with a free and open-source landing page for Tailwind CSS built with the Flowbite component library featuring dark mode, hero sections, pricing cards, and more.>
    <meta property="og:type" content="">
    <meta property="og:image" content=https://themesberg.s3.us-east-2.amazonaws.com/public/github/landwind/og-image.png>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@themesberg" />
    <meta name="twitter:creator" content="@themesberg" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/h/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/h/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('assets/h/site.webmanifest')}}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="./output.css" rel="stylesheet">
       @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</head>
<body>
    <header class="fixed w-full">
        <nav class="bg-white border-gray-200 py-2.5 dark:bg-gray-900">
            <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
                <a href="#" class="flex items-center">
                    <img src="{{asset('assets/h/images/logo.svg')}}" class="h-6 mr-3 sm:h-9" alt="Landwind Logo" />
                    <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Landwind</span>
                </a>
                <div class="flex items-center lg:order-2">
                    <button>
                </div>
                <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <a href="#" class="block py-2 pl-3 pr-4 text-white bg-purple-700 rounded lg:bg-transparent lg:text-purple-700 lg:p-0 dark:text-white" aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="{{route('filament.admin.auth.login')}}" class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Log In</a>
                        </li>
                        <li>
                            <a href="{{route('filament.admin.auth.register')}}" class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Start block -->
   <section class="bg-white dark:bg-gray-900">
    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="mr-auto place-self-center lg:col-span-7">
            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">
                Empowering Communities through Volunteerism
            </h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                Our Volunteer Management System helps organizations engage, manage, and empower volunteers to make a real impact. Whether you're coordinating community events, managing volunteer hours, or matching volunteers to causes, we provide the tools you need to make a difference.
            </p>
            <div class="space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                <a href="{{route('filament.admin.auth.login')}}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Get Started</a>
            </div>
        </div>
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
            <img src="{{ asset('assets/h/images/hero.png') }}" alt="hero image">
        </div>                
    </div>
</section>

    <!-- End block -->
    <!-- Start block -->
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 pb-8 mx-auto lg:pb-16">
            <div class="grid grid-cols-2 gap-8 text-gray-500 sm:gap-12 sm:grid-cols-3 lg:grid-cols-6 dark:text-gray-400">
                <a href="#" class="flex items-center lg:justify-center">
                              
                </a>
                <a href="#" class="flex items-center lg:justify-center">
                                                              
                </a>
                <a href="#" class="flex items-center lg:justify-center">
                                                                                
                </a>
    
                <a href="#" class="flex items-center lg:justify-center">
                                                                                                     
                </a>
                <a href="#" class="flex items-center lg:justify-center">
                                                                                                    
                </a>
            </div>
        </div>
    </section>
    <!-- End block -->
   
   <!-- Start block -->
<section class="bg-white dark:bg-gray-900">
    <div class="items-center max-w-screen-xl px-4 py-8 mx-auto lg:grid lg:grid-cols-4 lg:gap-16 xl:gap-24 lg:py-24 lg:px-6">
        <div class="col-span-2 mb-8">
            <p class="text-lg font-medium text-purple-600 dark:text-purple-500">Join Us Today</p>
            <h2 class="mt-3 mb-4 text-3xl font-extrabold tracking-tight text-gray-900 md:text-3xl dark:text-white">
                Trusted by over 500,000 volunteers and 3,000+ organizations worldwide
            </h2>
            <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">
                Our Volunteer Management System ensures seamless coordination, tracking, and empowerment for volunteers and organizations alike. Together, we're making a difference in communities across the globe.
            </p>
            <div class="pt-6 mt-6 space-y-4 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <a href="#" class="inline-flex items-center text-base font-medium text-purple-600 hover:text-purple-800 dark:text-purple-500 dark:hover:text-purple-700">
                        Learn More About Volunteering
                    </a>
                </div>
                <div>
                    <a href="#" class="inline-flex items-center text-base font-medium text-purple-600 hover:text-purple-800 dark:text-purple-500 dark:hover:text-purple-700">
                        Explore Volunteer Opportunities
                    </a>
                </div>
            </div>
        </div>
        <div class="col-span-2 space-y-8 md:grid md:grid-cols-2 md:gap-12 md:space-y-0">
            <div>
                <h3 class="mb-2 text-2xl font-bold dark:text-white">98% Volunteer Retention</h3>
                <p class="font-light text-gray-500 dark:text-gray-400">Helping organizations keep volunteers engaged and committed</p>
            </div>
            <div>
                <h3 class="mb-2 text-2xl font-bold dark:text-white">500,000+ Volunteers</h3>
                <p class="font-light text-gray-500 dark:text-gray-400">Volunteers from all over the world dedicated to creating impact</p>
            </div>
            <div>
                <h3 class="mb-2 text-2xl font-bold dark:text-white">3,000+ Organizations</h3>
                <p class="font-light text-gray-500 dark:text-gray-400">Partnering with non-profits, foundations, and community groups</p>
            </div>
            <div>
                <h3 class="mb-2 text-2xl font-bold dark:text-white">200+ Countries</h3>
                <p class="font-light text-gray-500 dark:text-gray-400">Where our platform is actively helping communities</p>
            </div>
        </div>
    </div>
</section>
<!-- End block -->

   
    <!-- Start block -->
   
    <!-- Start block -->
   <section class="bg-white dark:bg-gray-900">
    <div class="max-w-screen-xl px-4 pb-8 mx-auto lg:pb-24 lg:px-6 ">
        <h2 class="mb-6 text-3xl font-extrabold tracking-tight text-center text-gray-900 lg:mb-8 lg:text-3xl dark:text-white">Frequently Asked Questions</h2>
        <div class="max-w-screen-md mx-auto">
            <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
                
                <!-- Question 1 -->
                <h3 id="accordion-flush-heading-1">
                    <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-900 bg-white border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white" data-accordion-target="#accordion-flush-body-1" aria-expanded="true" aria-controls="accordion-flush-body-1">
                        How do I get started with volunteering?
                    </button>
                </h3>
                <div id="accordion-flush-body-1" class="" aria-labelledby="accordion-flush-heading-1">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Getting started is simple! Sign up on our platform, browse available opportunities, and apply for the ones you're interested in. Once you're matched, you'll receive guidance on next steps and how to contribute to your chosen cause.</p>
                        <p class="text-gray-500 dark:text-gray-400">Check out our <a href="#" class="text-purple-600 dark:text-purple-500 hover:underline">Volunteer Guide</a> to learn more about the process.</p>
                    </div>
                </div>

                <!-- Question 2 -->
                <h3 id="accordion-flush-heading-2">
                    <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
                        Do I need any prior experience to volunteer?
                    </button>
                </h3>
                <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Not necessarily! Many volunteer opportunities are open to individuals of all skill levels. We encourage everyone to get involved, regardless of experience. Some opportunities may require specific skills, but we provide training and support to help you get started.</p>
                    </div>
                </div>

                <!-- Question 3 -->
                <h3 id="accordion-flush-heading-3">
                    <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
                        How do I track my volunteer hours?
                    </button>
                </h3>
                <div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Our system allows you to easily log your volunteer hours after each activity. You'll be able to see your total hours over time and download reports for your records or for any requirements your organization may have.</p>
                    </div>
                </div>

                <!-- Question 4 -->
                <h3 id="accordion-flush-heading-4">
                    <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-4" aria-expanded="false" aria-controls="accordion-flush-body-4">
                        Can I volunteer remotely or virtually?
                    </button>
                </h3>
                <div id="accordion-flush-body-4" class="hidden" aria-labelledby="accordion-flush-heading-4">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Yes! Many of our volunteer opportunities can be done remotely or virtually, depending on the needs of the organization. Simply filter your search for virtual opportunities to find the perfect fit for you.</p>
                    </div>
                </div>

                <!-- Question 5 -->
                <h3 id="accordion-flush-heading-5">
                    <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-5" aria-expanded="false" aria-controls="accordion-flush-body-5">
                        How do organizations manage volunteers using this system?
                    </button>
                </h3>
                <div id="accordion-flush-body-5" class="hidden" aria-labelledby="accordion-flush-heading-5">
                    <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                        <p class="mb-2 text-gray-500 dark:text-gray-400">Organizations can post volunteer opportunities, manage volunteer applications, track hours, and communicate directly with volunteers. Our platform streamlines the entire volunteer management process to help organizations stay organized and efficient.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- End block -->
    <!-- Start block -->
  <section class="bg-gray-50 dark:bg-gray-800">
    <div class="max-w-screen-xl px-4 py-8 mx-auto lg:py-16 lg:px-6">
        <div class="max-w-screen-sm mx-auto text-center">
            <h2 class="mb-4 text-3xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white">Start managing volunteers today</h2>
            <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">Try our Volunteer Management Platform for 30 days, free of charge. No credit card required.</p>
            <a href="{{route('filament.admin.auth.login')}}" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">Start your free trial</a>
        </div>
    </div>
</section>
<!-- Start Footer -->
<footer class="bg-white dark:bg-gray-800">
    <div class="max-w-screen-xl p-4 py-6 mx-auto lg:py-16 md:p-8 lg:p-10">
            <!-- Company Info Section -->
        
        <!-- Footer Divider -->
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8">

        <!-- Footer Bottom Section -->
        <div class="text-center">
            <a href="#" class="flex items-center justify-center mb-5 text-2xl font-semibold text-gray-900 dark:text-white">
                <img src="{{ asset('assets/h/images/logo.svg') }}" class="h-6 mr-3 sm:h-9" alt="Volunteer Management Logo" />
                VolunteerHub    
            </a>
            <span class="block text-sm text-center text-gray-500 dark:text-gray-400">© 2024-2025 VolunteerHub™. All Rights Reserved. Developed by <a href="https://facebook.com/rao.bilal.167" class="text-purple-600 hover:underline dark:text-purple-500">Rao Bilal</a></span>
            
            <!-- Social Media Links -->
            <ul class="flex justify-center mt-5 space-x-5">
                <li>
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                        <!-- Twitter Icon -->
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                        <!-- Facebook Icon -->
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                        <!-- LinkedIn Icon -->
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                        <!-- Instagram Icon -->
                        <i class="fab fa-instagram"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                        <!-- YouTube Icon -->
                        <i class="fab fa-youtube"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>
<!-- End Footer -->
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
</body>
</html>
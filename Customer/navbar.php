<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="flex h-[82px] w-full items-center  bg-white shadow-md ">
        <div class="flex flex-row gap-3 justify-start   container sm:mx-auto mx-5">
            <div class="text-3xl text-[#FF8000] font-bold">Foodies</div>
            <div class="flex flex-row mx-3 flex-1">
                <div class="flex items-center px-3 max-w-[200px] border-[#d4d4db] border-[1px] rounded-full">
                    <p class="  text-ellipsis py-1  line-clamp-1 text-sm">
                        406,A-2 Vrajbhumi Sec2 406,A-2 Vrajbhumi Sec2
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-map-pin">
                        <path
                            d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
            </div>
            <div class=" border-[#d4d4db] border-[1px] rounded-full px-3 py-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-search">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input placeholder="Search..." class="outline-none ps-2" type="text" name="txtSearch" />
            </div>
            <div
                class="text-md font-sans cursor-pointer font-bold text-[#572af8] border-[#d4d4db] border-[1px] rounded-full px-3 flex items-center">
                Login</div>

        </div>
    </div>
</body>

</html>
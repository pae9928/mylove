<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เกมทดสอบ iQ ปอ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }
        .rotate-y-180 { transform: rotateY(180deg); }
        .backface-hidden { backface-visibility: hidden; }
        .card-container { transform-style: preserve-3d; transition: transform 0.3s; }
        .card-container>div:first-child {
            background-color: #FFFFFF;
            transition: background-color 0.3s, transform 0.3s;
        }
        .card-container:hover>div:first-child {
            background-color: #8379797e;
            transform: scale(1.1);
            box-shadow: 0px 6px 16px rgba(0,0,0,0.35);
            z-index: 10;
        }
        @keyframes slideIn {
            0% { transform: translateX(-60px); opacity: 0; }
            70% { transform: translateX(10px); opacity: 1; }
            100% { transform: translateX(0); }
        }
        .animate-slide { animation: slideIn 0.35s ease-out; }
        @keyframes pop {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); }
        }
        .animate-pop { animation: pop 0.25s ease-out; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(to bottom,#cc3366be 0%,#d64b79be 30%,#e67899be 70%,#F3AFC6 90%,#ffffff 100%);">

<div class="bg-pink-100 w-[410px] h-[425px] p-12 rounded-md shadow-xl text-center">
    <h1 class="text-3xl font-bold mb-6">เกมทด<span class="text-red-600">iQ</span></h1>
    <h1 class="text-2xl font-bold mb-6">Level 1</h1>
    <h1 class="text-xl font-bold">หาภาพที่<span class="text-red-600">เหมือนกัน</span></h1>

    <div class="grid grid-cols-4 gap-2 max-w-[205px] mx-auto mt-[1rem]" id="gameBoard"></div>

    <h1 id="successText" class="text-sm font-bold mt-3 hidden animate-slide">เก่งมาก</h1>

    <div id="restartContainer" class="mt-2 hidden">
        <!-- ปุ่มไป Level 2 เปลี่ยนเป็น HTML ล้วน -->
        <a href="level2.html"
           class="inline-block rounded-full bg-[#C74760] w-[125px] h-[35px] font-bold 
                  text-white text-center leading-[35px]
                  transition-all duration-300 ease-out
                  hover:scale-110 hover:bg-[#9C2D4F] hover:shadow-lg">
            ไปต่อ
        </a>
    </div>
</div>

<script>
const gameBoard = document.getElementById("gameBoard");
let items = ["★","✦","♦︎","♥︎","♠︎","♣"];
let frontSymbol = "?";
let cards = [...items, ...items];
cards.sort(() => Math.random() - 0.5);

cards.forEach(value => {
    const card = document.createElement("div");
    card.className = "relative w-[45px] h-[45px] cursor-pointer card-container";
    card.dataset.value = value;

    card.innerHTML = `
        <div class="absolute inset-0 flex justify-center items-center 
                    bg-[#FFFFFF] rounded-xl shadow text-xl font-bold backface-hidden">
        ${frontSymbol}</div>
        <div class="absolute inset-0 flex justify-center items-center 
                    bg-[#C74760] rounded-xl shadow text-2xl font-bold rotate-y-180 backface-hidden">
        ${value}</div>
    `;
    gameBoard.appendChild(card);
});

let first=null, second=null, lock=false;

document.querySelectorAll("#gameBoard > div").forEach(card => {
    card.addEventListener("click", () => {
        if(lock || card===first || card.classList.contains("rotate-y-180")) return;
        card.classList.add("rotate-y-180");

        if(!first){ first=card; return; }
        second=card;

        if(first.dataset.value===second.dataset.value){
            first=null; second=null; checkAllMatched();
        } else {
            lock=true;
            setTimeout(()=>{
                first.classList.remove("rotate-y-180");
                second.classList.remove("rotate-y-180");
                first=null; second=null; lock=false;
            },700);
        }
    });
});

function showSuccessText(callback){
    const text="เก่งมาก";
    const element=document.getElementById("successText");
    element.textContent="";
    element.classList.add("animate-slide");

    let index=0;
    const interval=setInterval(()=>{
        element.textContent+=text[index];
        index++;
        if(index===text.length){
            clearInterval(interval);
            if(callback) callback();
        }
    },120);
}

function checkAllMatched(){
    const allMatched=[...document.querySelectorAll("#gameBoard > div")]
        .every(card => card.classList.contains("rotate-y-180"));

    if(allMatched){
        const success=document.getElementById("successText");
        const restart=document.getElementById("restartContainer");
        success.classList.remove("hidden");
        showSuccessText(()=>{
            setTimeout(()=>{ restart.classList.remove("hidden"); restart.classList.add("animate-pop"); },200);
        });
    }
}
</script>

</body>
</html>

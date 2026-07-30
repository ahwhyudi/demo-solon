let socket = io('https://7b90-180-254-67-117.ngrok-free.app', {
    extraHeaders: {
        'ngrok-skip-browser-warning': 'true'
    }
});

socket.on('connect', () => {
    console.log('Berhasil Terkoneksi Dengan Socket Local');
});

socket.on('connect_error', (err) => {
    console.error('Connection Error:', err);
});

socket.on('disconnect', () => {
    console.log('Disconnected dari Socket Local');
});

socket.on('antrianDipanggil', (data) => {
    console.log('Nomor Antrian Dipanggil:', data);

    if (data.no.startsWith('A')) {
        document.querySelector('.f-treatment').innerText = data.no;
    } else if (data.no.startsWith('B')) {
        document.querySelector('.f-produk').innerText = data.no;
    }
    
    const uttr = new SpeechSynthesisUtterance();
    const audio = new Audio("/audio/bell-new.mp3");

    audio.addEventListener("ended", function() {
        uttr.text = `Nomor Antrian . ${data.no} . Silahkan Menuju Ke Kasir`;
        uttr.rate = 0.8;
        uttr.pitch = 1.0;
        uttr.volume = 0.9;
        uttr.voice = speechSynthesis
            .getVoices()
            .filter(voice => voice.name == "Google Bahasa Indonesia")[0];
        speechSynthesis.speak(uttr);
    });

    audio.play();
});

console.log(socket);

@extends('layouts.admin')

@section('title')
    WhatsApp Gateway
@endsection

@push('addStyle')
    <style>
        .wa-status-card {
            overflow: hidden;
        }

        .wa-status-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background: rgba(32, 107, 196, 0.08);
            color: var(--tblr-primary);
        }

        .wa-status-icon.is-connected {
            background: rgba(47, 179, 68, 0.1);
            color: var(--tblr-green);
        }

        .wa-status-icon.is-disconnected {
            background: rgba(214, 57, 57, 0.1);
            color: var(--tblr-red);
        }

        .wa-qr-box {
            min-height: 320px;
            border: 1px dashed var(--tblr-border-color);
            border-radius: 0.5rem;
            background: var(--tblr-bg-surface-secondary);
        }

        .wa-qr-box canvas,
        .wa-qr-box img {
            width: 240px;
            height: 240px;
            max-width: 100%;
            border-radius: 0.5rem;
            background: #fff;
            padding: 0.75rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.06);
        }

        .wa-step-number {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 2rem;
        }
    </style>
@endpush

@section('content')
    <div class="row row-cards">
        <div class="col-lg-5">
            <div class="card wa-status-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="text-secondary text-uppercase fw-bold small mb-2">Status Client</div>
                            <h3 class="mb-1" id="statusTitle">Memeriksa koneksi...</h3>
                            <div class="text-secondary" id="statusSubtitle">
                                Menghubungi WhatsApp Gateway.
                            </div>
                        </div>
                        <div class="wa-status-icon" id="statusIcon">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                    </div>

                    <div class="hr-text">Koneksi</div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-secondary small mb-1">Gateway</div>
                                <div class="fw-bold text-truncate" id="gatewayHost">localhost:3000</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-secondary small mb-1">Terakhir dicek</div>
                                <div class="fw-bold" id="lastChecked">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert mt-3 mb-0 d-none" id="gatewayAlert" role="alert"></div>
                </div>
                <div class="card-footer d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary" id="refreshStatusBtn">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Cek Status
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="loadQrBtn">
                        <i class="bi bi-qr-code me-1"></i>
                        Ambil QR Code
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <h3 class="card-title mb-0">QR Code WhatsApp</h3>
                        <div class="text-secondary small">Scan saat client belum tersambung.</div>
                    </div>
                    <span class="badge bg-secondary-lt" id="qrBadge">Belum dimuat</span>
                </div>
                <div class="card-body">
                    <div class="wa-qr-box d-flex align-items-center justify-content-center text-center p-4" id="qrBox">
                        <div class="empty">
                            <div class="empty-img">
                                <i class="bi bi-qr-code-scan fs-1 text-secondary"></i>
                            </div>
                            <p class="empty-title">QR Code belum dimuat</p>
                            <p class="empty-subtitle text-secondary">
                                Klik tombol Ambil QR Code untuk menampilkan kode login WhatsApp.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary text-white wa-step-number">1</span>
                                <div class="small">
                                    <div class="fw-bold">Buka WhatsApp</div>
                                    <div class="text-secondary">Masuk ke perangkat tertaut.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary text-white wa-step-number">2</span>
                                <div class="small">
                                    <div class="fw-bold">Scan QR</div>
                                    <div class="text-secondary">Arahkan kamera ke kode.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary text-white wa-step-number">3</span>
                                <div class="small">
                                    <div class="fw-bold">Cek Status</div>
                                    <div class="text-secondary">Pastikan client terhubung.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title mb-0">Kirim Pesan Percobaan</h3>
                        <div class="text-secondary small">Gunakan untuk memastikan gateway benar-benar bisa mengirim pesan.
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="sendMessageForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="messageTo" class="form-label required">Nomor Tujuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-phone"></i>
                                    </span>
                                    <input type="text" class="form-control" id="messageTo" name="to"
                                        placeholder="081234567890" autocomplete="off" required>
                                </div>
                                <small class="form-hint">Bisa memakai awalan 08 atau 62 sesuai format gateway.</small>
                            </div>
                            <div class="col-md-8">
                                <label for="messageText" class="form-label required">Isi Pesan</label>
                                <textarea class="form-control" id="messageText" name="message" rows="4"
                                    placeholder="Tulis pesan yang akan dikirim..." required></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="form-hint">Pastikan WhatsApp sudah tersambung sebelum mengirim.</small>
                                    <small class="text-secondary"><span id="messageCounter">0</span> karakter</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert mt-3 mb-0 d-none" id="sendMessageAlert" role="alert"></div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success" id="sendMessageBtn">
                                <i class="bi bi-send me-1"></i>
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addScript')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        // const baseUrl = 'http://localhost:3000';
        const baseUrl = 'https://gateway.kusumait.my.id';
        const apiKey = 'api-keygusti';

        const statusTitle = $('#statusTitle');
        const statusSubtitle = $('#statusSubtitle');
        const statusIcon = $('#statusIcon');
        const lastChecked = $('#lastChecked');
        const gatewayAlert = $('#gatewayAlert');
        const refreshStatusBtn = $('#refreshStatusBtn');
        const loadQrBtn = $('#loadQrBtn');
        const qrBox = $('#qrBox');
        const qrBadge = $('#qrBadge');
        const sendMessageForm = $('#sendMessageForm');
        const messageTo = $('#messageTo');
        const messageText = $('#messageText');
        const messageCounter = $('#messageCounter');
        const sendMessageAlert = $('#sendMessageAlert');
        const sendMessageBtn = $('#sendMessageBtn');

        $('#gatewayHost').text(baseUrl.replace(/^https?:\/\//, ''));

        const requestGateway = (path) => {
            return $.ajax({
                url: baseUrl + path,
                method: 'GET',
                dataType: 'json',
                headers: {
                    'x-api-key': apiKey,
                },
                timeout: 12000,
            });
        };

        const setBusy = (button, busy) => {
            button.prop('disabled', busy);
            button.find('i').toggleClass('spinner-border spinner-border-sm', busy);
        };

        const setSendAlert = (type, message) => {
            sendMessageAlert
                .removeClass('d-none alert-success alert-warning alert-danger')
                .addClass('alert-' + type)
                .text(message);
        };

        const showAlert = (type, message) => {
            gatewayAlert
                .removeClass('d-none alert-success alert-warning alert-danger')
                .addClass('alert-' + type)
                .text(message);
        };

        const hideAlert = () => {
            gatewayAlert.addClass('d-none').text('');
        };

        const updateStatusUi = (ready) => {
            lastChecked.text(new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }));

            statusIcon.removeClass('is-connected is-disconnected');

            if (ready) {
                statusTitle.text('WhatsApp tersambung');
                statusSubtitle.text('Gateway siap mengirim dan menerima pesan.');
                statusIcon.addClass('is-connected');
                qrBadge.removeClass().addClass('badge bg-success-lt').text('Terhubung');
                hideAlert();
                return;
            }

            statusTitle.text('WhatsApp belum tersambung');
            statusSubtitle.text('Ambil QR Code lalu scan melalui aplikasi WhatsApp.');
            statusIcon.addClass('is-disconnected');
            qrBadge.removeClass().addClass('badge bg-warning-lt').text('Perlu scan');
            showAlert('warning', 'Client belum ready. Tampilkan QR Code untuk menyambungkan perangkat.');
        };

        const setQrState = (state, message = '') => {
            const states = {
                loading: ['bg-primary-lt', 'Memuat QR'],
                ready: ['bg-success-lt', 'Siap scan'],
                empty: ['bg-secondary-lt', 'Belum dimuat'],
                error: ['bg-danger-lt', 'Gagal dimuat'],
            };

            const selected = states[state] || states.empty;
            qrBadge.removeClass().addClass('badge ' + selected[0]).text(selected[1]);

            if (message) {
                qrBox.html(message);
            }
        };

        const renderQr = (qr) => {
            if (!qr) {
                setQrState('error', `
                    <div class="empty">
                        <p class="empty-title">QR Code tidak tersedia</p>
                        <p class="empty-subtitle text-secondary">Gateway belum mengirim data QR. Coba ambil ulang beberapa detik lagi.</p>
                    </div>
                `);
                return;
            }

            qrBox.empty();

            if (/^(data:image\/|https?:\/\/)/i.test(qr)) {
                $('<img>', {
                    src: qr,
                    alt: 'QR Code WhatsApp'
                }).appendTo(qrBox);
            } else if (window.QRCode) {
                new QRCode(qrBox.get(0), {
                    text: qr,
                    width: 240,
                    height: 240,
                    correctLevel: QRCode.CorrectLevel.H,
                });
            } else {
                qrBox.html(`
                    <div class="empty">
                        <p class="empty-title">Library QR belum termuat</p>
                        <p class="empty-subtitle text-secondary">Refresh halaman lalu coba lagi.</p>
                    </div>
                `);
                setQrState('error');
                return;
            }

            setQrState('ready');
        };

        const checkStatus = () => {
            setBusy(refreshStatusBtn, true);

            requestGateway('/whatsapp/status')
                .done(function(res) {
                    updateStatusUi(Boolean(res.ready));
                })
                .fail(function(xhr) {
                    lastChecked.text(new Date().toLocaleTimeString('id-ID'));
                    statusTitle.text('Gateway tidak merespons');
                    statusSubtitle.text('Pastikan service WhatsApp Gateway sedang berjalan.');
                    statusIcon.removeClass('is-connected').addClass('is-disconnected');
                    showAlert('danger', `Gagal cek status gateway. HTTP ${xhr.status || 'timeout'}.`);
                })
                .always(function() {
                    setBusy(refreshStatusBtn, false);
                });
        };

        const getQrCode = () => {
            setBusy(loadQrBtn, true);
            setQrState('loading', `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <div class="fw-bold">Mengambil QR Code...</div>
                </div>
            `);

            requestGateway('/whatsapp/qr')
                .done(function(res) {
                    renderQr(res.qr || res.data || res.image || res.qrcode);
                    checkStatus();
                })
                .fail(function(xhr) {
                    setQrState('error', `
                        <div class="empty">
                            <div class="empty-img">
                                <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                            </div>
                            <p class="empty-title">Gagal mengambil QR Code</p>
                            <p class="empty-subtitle text-secondary">HTTP ${xhr.status || 'timeout'}. Pastikan gateway aktif dan API key sesuai.</p>
                        </div>
                    `);
                })
                .always(function() {
                    setBusy(loadQrBtn, false);
                });
        };

        const sendTextMessage = () => {
            const payload = {
                to: messageTo.val().trim(),
                message: messageText.val().trim(),
            };

            if (!payload.to || !payload.message) {
                setSendAlert('warning', 'Nomor tujuan dan isi pesan wajib diisi.');
                return;
            }

            setBusy(sendMessageBtn, true);
            sendMessageAlert.addClass('d-none').text('');

            $.ajax({
                    url: baseUrl + '/whatsapp/send-text',
                    method: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(payload),
                    headers: {
                        'x-api-key': apiKey,
                    },
                    timeout: 15000,
                })
                .done(function(res) {
                    setSendAlert('success', 'Pesan berhasil dikirim ke ' + payload.to + '.');
                    messageText.val('');
                    messageCounter.text('0');
                    checkStatus();
                    console.log('Pesan terkirim:', res);
                })
                .fail(function(xhr) {
                    let errorMessage = 'Gagal mengirim pesan.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }

                    setSendAlert('danger', `HTTP ${xhr.status || 'timeout'} - ${errorMessage}`);
                    console.error('Gagal kirim text:', xhr.status, xhr.responseText);
                })
                .always(function() {
                    setBusy(sendMessageBtn, false);
                });
        };

        refreshStatusBtn.on('click', checkStatus);
        loadQrBtn.on('click', getQrCode);
        messageText.on('input', function() {
            messageCounter.text($(this).val().length);
        });
        sendMessageForm.on('submit', function(event) {
            event.preventDefault();
            sendTextMessage();
        });

        checkStatus();
        setInterval(checkStatus, 30000);
    </script>
@endpush

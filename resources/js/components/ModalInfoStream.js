import Modal from "react-bootstrap/Modal";
import Button from "react-bootstrap/Button";
import '../../css/studio-main.css'

function ModalInfoStream({handleClose, showPopUp, email, passToken, host, port, path, streamKey, sessionId}){
    return (
        <Modal
                show={showPopUp}
                onHide={handleClose}
                size="lg"
                aria-labelledby="contained-modal-title-vcenter"
                centered
                backdrop="static"
                keyboard={false}
                className="modal-info"
            >
                <Modal.Header closeButton>
                    <Modal.Title className="fw-bold text-danger">PENTING !!!</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <h3 className="w-100 text-center mb-3">Pentunjuk Menjalankan Streaming / Siaran</h3>
                    <p>
                        Untuk memulai streaming atau siaran video, anda dapat menggunakan dua opsi metode streaming. Adapun opsi beserta penjelasannya sebagai berikut : 
                    </p>
                    <h5 className="text-primary">1. Menggunakan OBS Studio</h5>
                    <p>Opsi streaming ini dapat anda pilih jika anda menginginkan streaming dengan resolusi video diatas 1920 x 1080 pixel (FHD). Serta nilai FPS dan layout video yang bebas diatur sesuai kebutuhan streamer. 
                        Dan juga disarankan menggunakan metode ini apabila anda memiliki discreate grapich card pada perangkat streamer anda untuk mendapatkan hasil video yang lancar. 
                        <a className="text-primary" href="https://obsproject.com/download">Klik disini untuk download</a>. Adapun tata caranya koneksi ke server kami sebagai berikut :</p>
                    <ul>
                        <li>Buka menu file &gt; Settings &gt; Stream</li>
                        <li>Kolom service pilih <span className="text-damger" style={{overflowWrap: "anywhere"}}>custom</span></li>
                        <li>Server = <span className="text-danger" style={{overflowWrap: "anywhere"}}>rtmp://{host}:{port}{path}/</span></li>
                        <li>Stream Key = <span className="text-danger" style={{overflowWrap: "anywhere"}}>{streamKey}?email={email}&password=**********&session={sessionId}</span></li>
                        <li>Untuk password dapat anda isi dengan password login anda atau dengan token berikut : <span className="text-danger" style={{overflowWrap: "anywhere"}}>{passToken}</span></li>
                    </ul>
                    <h5 className="mt-3 text-primary">2. Menggunakan Agendakota Studio (Pada Halaman Ini)</h5>
                    <p>Opsi ini dapat anda pilih jika aktivitas streaming anda cukup ringan dengan reoslusi video kurang dari sama dengan 1920 x 1080 pixel (FHD). Serta tidak membutuhkan banyak layout video
                        dalam satu canvas. Agendakota studio memiliki beberapa batasan mengingat studio app ini berjalan pada browser. Diantaranya yaitu :
                    </p>
                    <ul>
                        <li>Resolusi maksimal canvas yaitu 1920 x 1080 pixel (FHD)</li>
                        <li>Nilai FPS dikunci pada 24 fps</li>
                        <li>Tidak dapat memanfaatkan discreate grapich card apabila mempunyainya (Dikarenakn berjalan pada browser)</li>
                        <li>Tersedia tiga empat macam layout video canvas yaitu single screen, split screen, split triple screen, split four screen, dan toturial mode</li>
                    </ul>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleClose}>
                        Close
                    </Button>
                </Modal.Footer>
            </Modal>
    )
}

export default ModalInfoStream
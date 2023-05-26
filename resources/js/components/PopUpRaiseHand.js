import Modal from "react-bootstrap/Modal";
import Draggable from "react-draggable";
import Button from "react-bootstrap/Button";
import React, { useEffect, useState } from "react";
// const {ReactDraggable: Draggable, React, ReactDOM} = window;

const getInitial = (name) => {
    let initial = "";
    name.split(" ").forEach(part => {
        initial += part[0];
    });
    return initial;
};

const getColor = (initial) => {
    initial = initial.toLowerCase()
    let asciiCode = 0
    for (let i = 0; i < initial.length; i++) {
        asciiCode += initial.charCodeAt(i)
    }
    let colorNum = asciiCode.toString() + asciiCode.toString() + asciiCode.toString()
    let num = Math.round(0xffffff * parseInt(colorNum))

    let r = num >> 16 & 255
    let g = num >> 8 & 255
    let b = num & 255

    console.log(`rgb(${r}, ${g}, ${b}, 0.3)`);
    return `rgb(${r}, ${g}, ${b}, 0.3)`
}

function DraggableModalDialog({ title, socket, refBtnTrigger, refBtnLower, username }) {
    const [activeDrags, setActiveDrags] = useState(0);
    const [dragHandlers, setHandlers] = useState({});
    const [dataShow, setDataShow] = useState([]);
    const [show, setShow] = useState(false);
    const [iconColor, setColor] = useState('#fff')

    const timeShow = 5000; //dalam mili detik

    const closePopUp = () => {
        setShow(false)
    }

    useEffect(() => {
        const onStart = () => {
            setActiveDrags(activeDrags + 1);
        };

        const onStop = () => {
            setActiveDrags(activeDrags - 1);
        };

        setHandlers({ onStart, onStop });
    }, [activeDrags]);

    useEffect(() => {
        if (socket && username) {
            refBtnTrigger.current.addEventListener("click", () => {
                socket.emit("command-raise-hand", username);
                refBtnTrigger.current.classList.add('d-none')
                refBtnLower.current.classList.remove('d-none')
            });

            refBtnLower.current.addEventListener('click', () => {
                socket.emit('command-lower-hand', username)
                refBtnTrigger.current.classList.remove('d-none')
                refBtnLower.current.classList.add('d-none')
            })

            socket.on("raise-hand", username => {
                let tmp = dataShow;
                if(tmp.indexOf(username) === -1){
                    tmp.push(username);
                    setColor(`#${Math.floor(
                        Math.random() *
                            16777215
                    ).toString(16)}`)
                    setDataShow(tmp);
                    setShow(true);
                }
            });

            socket.on('lower-hand', username => {
                let tmp = dataShow
                let index = tmp.indexOf(username)
                if(index !== -1){
                    tmp.splice(index, 1)
                    setDataShow(dataShow.filter(d => d !== username))
                    if(tmp.length === 0){
                        setShow(false)
                    }
                }
            })
        }
    }, [socket, username]);

    return (
        <Draggable bounds="body" {...dragHandlers}>
            <div
                className={`modal ${show ? 'show' : ''}`}
                style={{
                    position: "absolute",
                    height: "unset",
                    width: "unset"
                }}
            >
                <Modal.Dialog>
                    <Modal.Header className="pop-up-raise-hand">
                        <Modal.Title className="me-4">{title + ' '}</Modal.Title>
                    </Modal.Header>

                    <Modal.Body>
                        <div className="row pop-upp-raise-hand-size">
                            {dataShow.map(username => {
                                return (
                                    <div key={username} className="col-12 mb-3">
                                        <div className="row">
                                            <div className="col-4">
                                                <div
                                                    className="w-100 rounded-circle"
                                                    style={{
                                                        aspectRatio: "1/1",
                                                        backgroundColor: getColor(getInitial(username)),
                                                        display: 'flex',
                                                        alignItems: 'center',
                                                        justifyContent: 'center',
                                                        fontSize: '1.5rem',
                                                        maxWidth: '70px'
                                                    }}
                                                >
                                                    {getInitial(username)}
                                                </div>
                                            </div>
                                            <div className="col-8" style={{
                                                display: 'flex',
                                                justifyContent: 'center',
                                                alignItems: 'center'
                                            }}>
                                                {username}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </Modal.Body>

                    <Modal.Footer className="d-flex m-auto">
                        <Button variant="danger" onClick={closePopUp}>Close Pop Up</Button>
                    </Modal.Footer>
                </Modal.Dialog>
            </div>
        </Draggable>
    );
}

export default DraggableModalDialog;

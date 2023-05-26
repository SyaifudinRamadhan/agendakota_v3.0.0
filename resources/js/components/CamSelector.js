import { useEffect, useState } from "react";

// let counter = 0
function CamSelector({ id, changeCamFn, changeResFn, submitHandle, value }) {
    // counter ++
    // console.log(counter, "ini counter di cmaera list");
    const [defVal, setDefVal] = useState(null)

    useEffect(() => {
        setDefVal(value)
    })

    return (
        <div className="row mb-3">
            <div className="col-md-6">
                <label>Camera {id}</label>
                <select
                    id={id}
                    key={id}
                    className="form-select mb-3 list-select"
                    onChange={changeCamFn}
                >
                    <option>--- No selected devices ----</option>
                </select>
            </div>
            <div className="col-md-6">
                {/* {console.log(value)} */}
                <form onSubmit={submitHandle}>
                    <label>Camera Resolution</label>
                    <input
                        id={id}
                        className="form-control"
                        placeholder="Default resolution optimzed to canvas"
                        name="newResolution"
                        // value={value}
                        // ref={(el) => {refValue.current[id - 1] = el}}
                        defaultValue={defVal}
                        onChange={changeResFn}
                    ></input>
                    <div className="d-flex">
                        <div className="form-check">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                value={true}
                                id="center-x"
                            ></input>
                            <label className="form-check-label" htmlFor="center-x">
                                Center X
                            </label>
                        </div>
                        <div className="form-check ms-3">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                value={true}
                                id="center-y"
                            ></input>
                            <label className="form-check-label" htmlFor="center-y">
                                Center Y
                            </label>
                        </div>
                    </div>
                    <p className="text-danger">
                        *Example input resolution 1920x1080
                    </p>
                    <button className="btn btn-primary" type="submit">
                        Apply Resolution
                    </button>
                </form>
            </div>
        </div>
    );
}

export default CamSelector;

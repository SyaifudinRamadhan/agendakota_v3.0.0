function Profile({myData, nav=true}){
    console.log(nav);
    return(
        <div>
            <div className="row">
                <div className="col-12 text-center d-flex">
                    <img src="/images/icon-ak.png" className="rounded-circle mx-auto my-auto" width={'80px'} height={'80px'}></img> 
                </div>
                <div className="col-12 text-center d-flex">
                    <h4 className="w-100 text-center mt-4">{myData.name}</h4>
                </div>
                <div className="col-12 text-center d-flex">
                    <h5 className="w-100 text-center">{myData.email}</h5>
                </div>
                {nav ? (
                    <>
                    <div className="col-12 text-center d-flex mt-3">
                        <a className="text-decoration-none text-dark text-center fs-5 w-100 mb-3" href="/">Homepage</a>
                    </div>
                    <div className="col-12 text-center d-flex">
                        <a className="text-decoration-none text-dark text-center fs-5 w-100 mb-3" href="/myTickets">My Tickets</a>
                    </div>
                    <div className="col-12 text-center d-flex">
                        <a className="text-decoration-none text-dark text-center fs-5 w-100 mb-3" href="/profilePage">My Profile</a>
                    </div>
                    <div className="col-12 text-center d-flex mt-3">
                        <a className="text-decoration-none btn btn-danger w-100" href="/logout">Logout</a>
                    </div>
                    </>
                ) : ('')}
            </div>
        </div>
    )
}

export default Profile
import Button from 'react-bootstrap/Button';
import * as Icon from 'react-bootstrap-icons';
import '../../css/sidebar.css'

function Sidebar({orgId, eventId, category, breakdown, ...props}){



    return (
        <div className="p-3 sidebar-container" {...props}>
            <div className="row">
                <div className="col-12 pb-3">
                    <a href={`/organization/${orgId}/profil`}>
                        <Button variant="danger" className='w-100 fs-sidebar text-white'>
                            <Icon.ArrowLeft /> &nbsp;
                            Kembali
                        </Button>
                    </a>
                </div>

                <div className="col-12 pb-3">
                    <a href={`/${orgId}/event/${eventId}/event-overview`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <i className="fa fa-home me-2"></i>
                        <p className="d-inline my-auto">Event Overview</p>
                    </a>
                </div>

                <div className="col-12">
                    <h4 className="text-start fs-sidebar-cat mt-3 ms-2 me-2">EVENT DETAILS</h4>
                </div>
                
                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/edit`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <i className="fa fa-edit me-2 my-auto"></i>
                        <p className="d-inline my-auto">Basic Info </p>
                    </a>
                </div>
                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/event-code-user-scan`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <Icon.QrCode className='me-2 my-auto'></Icon.QrCode>
                        <p className="d-inline my-auto">QR Event </p>
                    </a>
                </div>
                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/ticket`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <i className="fa fa-tag me-2 my-auto"></i>
                        <p className="d-inline my-auto">TIcket & Pricing </p>
                    </a>
                </div>
                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/handbooks`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <i className="fa fa-file me-2 my-auto"></i>
                        <p className="d-inline my-auto">HandBook</p>
                    </a>
                </div>

                <div className="col-12">
                    <h4 className="text-start fs-sidebar-cat mt-4 ms-2 me-2">EVENT REPORTS</h4>
                </div>

                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/ticketSelling`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <Icon.CashStack className='me-2 my-auto'></Icon.CashStack>
                        <p className="d-inline my-auto">Ticket Selling</p>
                    </a>
                </div>
                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/ticketSelling/qr-checkin`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <Icon.QrCodeScan className='me-2 my-auto'></Icon.QrCodeScan>
                        <p className="d-inline my-auto">QR Checkin</p>
                    </a>
                </div>

                <div className="col-12">
                    <h4 className="text-start fs-sidebar-cat mt-4 ms-2 me-2">EVENT SPACE</h4>
                </div>

                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/receptionist`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <i className="fa fa-clipboard me-2 my-auto"></i>
                        <p className="d-inline my-auto">Receptionist</p>
                    </a>
                </div>
                <div className="col-12">
                    <a href={`/${orgId}/event/${eventId}/rundown`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                        <Icon.Calendar className='me-2 my-auto'></Icon.Calendar>
                        <p className="d-inline my-auto">Rundowns</p>
                    </a>
                </div> 
                {breakdown.match(/Stage and Session/gi) ? (
                    <div className="col-12">
                        <a href={`/${orgId}/event/${eventId}/session`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                            <i className='fa fa-camera me-2 my-auto'></i>
                            <p className="d-inline my-auto">Stage & sessions</p>
                        </a>
                    </div> 
                ):(
                    <div className="col-12">
                        <a href={`/${orgId}/event/${eventId}/session/config`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                            <Icon.Gear className='me-2 my-auto'></Icon.Gear>
                            <p className="d-inline my-auto">Session Config</p>
                        </a>
                    </div> 
                )}


                { category.match(/Seminar/gi) || category.match(/Conference/gi) || category.match(/Symposium/gi) || category.match(/Workshop/gi) ||category.match(/Talkshow/gi) ||category.match(/Live Music & Concert/gi) ||category.match(/Show & Festival/gi) ||breakdown.match(/Exihibitors/gi) ||breakdown.match(/Sponsors/gi) ? (
                    <div className="col-12">
                        <h4 className="text-start fs-sidebar-cat mt-4 ms-2 me-2">ROLES</h4>
                    </div>
                ) : ('') }

                {category.match(/Seminar/gi) || category.match(/Conference/gi) || category.match(/Symposium/gi) || category.match(/Workshop/gi) ||category.match(/Talkshow/gi) ||category.match(/Live Music & Concert/gi) ||category.match(/Show & Festival/gi) ? (
                    <div className="col-12">
                        <a href={`/${orgId}/event/${eventId}/speaker`} className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                            <i className="fa fa-microphone-alt me-2 my-auto"></i>
                            {category.match(/Live Music & Concert/gi) ||category.match(/Show & Festival/gi) ? (
                                <p className="d-inline my-auto">Peformers</p>
                            ) : (
                                <p className="d-inline my-auto">Speakers</p>
                            )}
                        </a>
                    </div>
                ) : ('')}
                {breakdown.match(/Exihibitors/gi) ? (
                    <>
                    <div className="col-12">
                        <a href={`/${orgId}/event/${eventId}/booth-categories`}  className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                            <i className='fa fa-building me-2 my-auto'></i>
                            <p className="d-inline my-auto">Booth Category</p>
                        </a>
                    </div> 
                    <div className="col-12">
                        <a href={`/${orgId}/event/${eventId}/exhibitor`}  className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                            <i className='fa fa-chalkboard-teacher me-2 my-auto'></i>
                            <p className="d-inline my-auto">Exihibitors</p>
                        </a>
                    </div> 
                    </>
                ) : ('')}
                {breakdown.match(/Sponsors/gi) ? (
                    <div className="col-12">
                        <a href={`/${orgId}/event/${eventId}/sponsor`}  className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                            <i className='fa fa-tv me-2 my-auto'></i>
                            <p className="d-inline my-auto">Sponsors</p>
                        </a>
                    </div> 
                ) : ('')}
                {breakdown.match(/Media Partner/gi) ? (
                    <div className="col-12">
                        <a href={`/${orgId}/event/${eventId}/media-partners`}  className='d-flex text-decoration-none ms-4 me-4 fs-sidebar mt-3'>
                            <Icon.Postcard className='me-2 my-auto'></Icon.Postcard>
                            <p className="d-inline my-auto">Media Partner</p>
                        </a>
                    </div> 
                ):('')}

            </div>
        </div>
    )
}


export default Sidebar


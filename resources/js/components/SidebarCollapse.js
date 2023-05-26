import Sidebar from "./Sidebar";
import Profile from "./Profile";
import '../../css/sidebar-collapse.css'

function SidebarCollapse({myData, orgId, eventId, category, breakdown}){
    return (
        <div>
            <Profile myData={myData} nav={false}></Profile>
            <hr></hr>
            <Sidebar className="mt-3" orgId={orgId} eventId={eventId} category={category} breakdown={breakdown} style={{
                position: 'unset !important',
                height: 'unset !important',
                overflowY: 'scroll',
                overflowX: "hidden",
                width: '100% !important'
                }}
            ></Sidebar>
            <a className="text-decoration-none btn btn-danger w-100 mt-5 mb-3" href="/logout">Logout</a>
        </div>
    )
}

export default SidebarCollapse
import Container from 'react-bootstrap/Container';
import React, { useState } from 'react';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';
import Offcanvas from 'react-bootstrap/Offcanvas';
import '../../css/navbar.css'
import { placements } from '@popperjs/core';
import Profile from './Profile';
import SidebarCollapse from './SidebarCollapse';

function NavTopBar({myData, orgId, eventId, category, breakdown}) {

  const [show, setShow] = useState(false);
  const [showProfile, setShowProfile] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const handleCloseProfile = () => setShowProfile(false);
  const handleShowProfile = () => setShowProfile(true);

  return (
    <Navbar bg="light" expand="lg">
      <Container className='container__mod'>
        <Navbar.Brand className='d-flex' href="/"><img src='/images/logo.png' height={'40px'}></img><h5 className='my-auto fw-bold'>| Studio</h5></Navbar.Brand>
        <Navbar.Toggle onClick={handleShow} />
        <Navbar.Collapse>
          <Nav className="ms-auto" id='collapse-navbar'>
            <img src='/images/icon-ak.png' width="50px" height="50px" className='rounded-circle pointer' onClick={handleShowProfile}></img>  
          </Nav>
        </Navbar.Collapse>
        {/* =========== Offcnvas side menu ===================== */}
        <Offcanvas show={show} onHide={handleClose} placement="start">
        <Offcanvas.Header closeButton>
          <Offcanvas.Title>Side Menu</Offcanvas.Title>
        </Offcanvas.Header>
        <Offcanvas.Body>
          <SidebarCollapse orgId={orgId} eventId={eventId} category={category} breakdown={breakdown} myData={myData}></SidebarCollapse>
        </Offcanvas.Body>
      </Offcanvas>
      {/* ============ Offcanvas profile ====================== */}
      <Offcanvas show={showProfile} onHide={handleCloseProfile} placement="end">
        <Offcanvas.Header closeButton>
          <Offcanvas.Title>Profile</Offcanvas.Title>
        </Offcanvas.Header>
        <Offcanvas.Body>
          <Profile myData={myData}></Profile>
        </Offcanvas.Body>
      </Offcanvas>
      </Container>
    </Navbar>
  );
}

export default NavTopBar;
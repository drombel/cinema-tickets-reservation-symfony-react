import React from 'react';
import { Link } from "react-router-dom";
// import './sectionListItem.scss';

export default (props) => {
    let {
        title = null,
        text = null,
        background = '#fff',
        link = null
    } = props;

    return(
        <Link to={link} style={{
            background: background,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
        }}>
            <div><span className="h3">{title}</span></div>
            <div><span className="h3">{title}</span></div>
        </Link>
    );
}
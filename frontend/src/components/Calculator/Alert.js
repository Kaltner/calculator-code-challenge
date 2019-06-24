import React, {Component} from 'react';
import PropTypes from 'prop-types';

class Alert extends Component {
    static propTypes = {
        alert: PropTypes.any,
        dismissAlert: PropTypes.func
    }

    componentWillUpdate() {
        if( this.dismissTimer ) clearTimeout(this.dismissTimer)
        this.dismissTimer = setTimeout(() => {
            this.props.dismissAlert()
        }, 4000)
    }
    
    render() {
        const {alert} = this.props;
        if(alert) {
            const className = `alert alert-${alert.type} fixed-top`;

            return (
                <div className={className} role="alert">
                    <span dangerouslySetInnerHTML={{__html: alert.message}}></span>
                    <button 
                        onClick={() => this.props.dismissAlert()}
                        type="button" className="close" aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            )
        }
        return "";
    }
}

export default Alert;
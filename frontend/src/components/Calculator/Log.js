import React, {Component} from 'react';
import PropTypes from 'prop-types';
import '../../stylesheets/log.scss';

class Log extends Component {
    procTypes = {
        getLogs: PropTypes.func,
        logs: PropTypes.array
    }

    state = {
        logs: []
    }

    componentDidMount() {
        this.props.getLogs();
        
        this.logTimer = setInterval(() => {
            this.props.getLogs()
        }, 10000)
    }

    render() {
        return (
          <div className="row">
            <div className="p-3 mb-2 log">
              <ul className="list-group">
                {this.props.logs.map((val, i) => (
                    <li 
                        key={i}
                        className="list-group-item text-left"
                    >
                        <span >
                            {val.relationships.elements.map((vEle, kEle) => (
                                vEle.element
                            ))}
                        </span>
                        <span className="float-right badge badge-primary">{val.attributes.total}</span>
                    </li>
                ))}
              </ul>
            </div>
          </div>  
        );
    }
}

export default Log;
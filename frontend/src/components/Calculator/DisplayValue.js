import React, {Component} from 'react';
import PropTypes from 'prop-types';

class DisplayValue extends Component {
    static propTypes = {
        calc_total: PropTypes.number,
        calc: PropTypes.shape({
            op: PropTypes.string,
            index: PropTypes.number
        }),
        current_focus: PropTypes.string

    }
    render() {
        const {op, index} = this.props.calc; 
        return (
            <span className="h4 text-left">
                <span 
                    className={index === this.props.current_focus ? 'bg-light text-dark' : '' }
                >
                    {op}
                </span> 
            </span>
        );
    }
}

export default DisplayValue;
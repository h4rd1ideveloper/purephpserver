import React, { useContext } from 'react';
import { Memorize } from '../../lib/helpers';
import { Context } from '../../store';

export default function ({ classes }) {
    const [{ header, isLoaded },] = useContext(Context)
    const stylesheet = {
        fontSize: 'smaller',
        overflowX: 'scroll'
    }
    return (
        Memorize({
            fact: () => {
                if (isLoaded) {
                    return (
                        <ul
                            style={stylesheet}
                            className={`${classes || 'list-group list-group-horizontal-sm'}`}
                        >
                            {
                                header.map(
                                    (title, index) => (<li key={index} className='list-group-item'>{title}</li>)
                                )
                            }
                        </ul>)
                }
                return <></>
            },
            deps: [classes, header, isLoaded]
        })
    );
};//
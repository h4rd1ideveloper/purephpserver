import React, { useContext } from 'react';
import { Memorize, verifyEmptyContent } from '../../lib/helpers/index';
import { Context } from '../../store/index';

export default function ({ classes }) {
    const [{ xlsx, isLoaded },] = useContext(Context)
    const stylesheet = {
        maxHeight: '61vh', overflowY: 'auto'
    }
    const items =
        xlsx.map(
            (row, index) => {
                return (
                    <li key={index}  className='list-group-item'>
                        <h2>Linha {`${index + 1}`}:</h2>
                        <ul className='list-group'>
                            {
                                (
                                    verifyEmptyContent(row).ok && <li className='list-group-item bg-success text-white-50' > OK </li>
                                ) || verifyEmptyContent(row).lines.map( (value, i) => (
                                    <li key={i} className='list-group-item bg-danger text-white-50'> coluna -> {`${value}`} Vazia </li>
                                ))
                            }
                        </ul>
                    </li>
                );
            }
        )
    return (
        Memorize({
            fact: () => {
                if (isLoaded) {
                    return (
                        <ul
                            style={stylesheet}
                            className={`${classes || 'list-group my-2'}`}
                        >
                            {items}
                        </ul>
                    )
                }
                return <><h2 className="h2 text-center text-black-50">Esperando Arquivo</h2></>
            },
            deps: [classes, xlsx, isLoaded]
        })
    );
};
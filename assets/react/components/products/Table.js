import { useState, useEffect } from 'react';
import Modal from '../products/Modal'
import Backdrop from '../products/Backdrop';

function Table(props) {
    const [products, setProducts] = useState([]);
    const headers = ['id', 'name', 'num'];
    const productDefault = {
        readonly: 'readonly',
        product: {
            id: null,
            name: '',
            num: '',
        },
    };
    const [modalIsOpen, setModalIsOpen] = useState(false);
    const [product, setProduct] = useState({
        readonly: 'readonly',
        product: productDefault,
    });

    function fetchProducts() {
        fetch(props.indexUrl)
            .then(response => response.json())
            .then(data => {
                console.debug(data.products);//mmmyyy
                setProducts(data.products);
            })
    }

    function saveProduct(name, num) {
        const url = product.product.id ? props.getEditUrl(product.product.id) : props.newUrl

        console.debug(url);//mmmyyy
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name, num: num })
        })
            .then(response => response.json())
            .then(data => {
                fetchProducts();
                setModalIsOpen(false);
            })
    }

    function showModal(readonly, product) {
        setModalIsOpen(true);
        setProduct({
            readonly: readonly,
            product: product,
        });
    }

    function closeModal(readonly, product) {
        console.debug('closeModal');//mmmyyy
        setModalIsOpen(false);
        setProduct({
            readonly: readonly,
            product: {
                readonly: 'readonly',
                product: productDefault,
            },
        });
    }

    useEffect(() => {
        fetchProducts();
    }, []);

    return (
        <>
            <div className="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
                <table className="w-full text-left table-auto min-w-max text-slate-800">
                    <thead>
                        <tr className="text-slate-500 border-b border-slate-300 bg-slate-50">
                            {headers.map(header => <th className="p-4" key={header}>{header}</th>)}
                            <th ><a className="p-4" onClick={() => showModal('', {})}>+ add</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        {products.map((product, index) => (
                            <tr className="hover:bg-slate-50" key={index}>
                                <td className="p-4">{product.id}</td>
                                <td className="p-4">{product.name}</td>
                                <td className="p-4">{product.num}</td>
                                <td>
                                    <a className="p-4" onClick={() => showModal('readonly', { id: product.id, name: product.name, num: product.num })}>show</a>
                                    <a className="p-4" onClick={() => showModal('', { id: product.id, name: product.name, num: product.num })}>Edit</a>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div >
            {modalIsOpen && (
                <Modal closeModal={closeModal} product={product} saveProduct={saveProduct} />
            )}
            {modalIsOpen && <Backdrop closeModal={closeModal} />}
        </>
    );
}

export default Table;

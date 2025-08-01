import Table from '../components/products/Table';

function Products(props) {
    function getEditUrl(productId) {
        return props.editUrl.replace('id-placeholder', productId);
    }

    return (
        <div id='products'>
            <Table
                indexUrl={props.indexUrl}
                newUrl={props.newUrl}
                getEditUrl={getEditUrl}
            />
        </div>
    );
}

export default Products;

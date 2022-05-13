import React, {Component} from 'react';
import { CKEditor } from '@ckeditor/ckeditor5-react';
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import ReactDOM from 'react-dom';
import {Modal} from "react-bootstrap";
import {
    deleteGalleryItem,
    getGalleryById,
    getGalleryItemPathById,
    storeGalleryItem,
    updateGalleryItem
} from "../actions";

const galleryElement = document.getElementById('gallery');
const galleryId = document.querySelector('input[name="gallery_id"]').value;

class GalleryContainer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            items: [],
            item: null,
            showItemPopup: false,
            types: ['item', 'cover', 'support', 'gallery']
        }

        this.table = React.createRef();
    }

    componentDidMount() {
        this.getItems();
    }

    getItems() {
        getGalleryById(galleryId).then(res => {
                this.setState({items: res.data.items})
            })
            .catch(err => {
                console.log(err);
            })
    }

    handleShowPopup(show = true, item = null) {
        this.setState({showItemPopup: show, item: item})
    }

    handlePopupSave() {
        this.handlePopupClose(() => {
            this.getItems()
        });
    }

    handlePopupClose(callback = null) {
        this.setState({
            showItemPopup: false,
            item: null,
        }, () => {
            if(typeof callback === 'function')
                callback();
        })
    }

    handleDeleteItem(id) {
        deleteGalleryItem(id).then(res => {
            if(res.data.status === 200) {
                let items = [...this.state.items];
                const index = items.findIndex(item => item.id === id);
                if(index !== -1) {
                    items.splice(index, 1);
                    this.setState({
                        items,
                    })
                }
            }
        })
    }

    render() {
        return (
            <div>
                <div className="card">
                    <div className="card-header">
                        Gallery
                        <div>
                            <button className="btn btn-primary"
                                    onClick={() => this.handleShowPopup(true, null)}>Add</button>
                            <Popup show={this.state.showItemPopup}
                                   item={this.state.item}
                                   types={this.state.types}
                                   handleClose={() => this.handlePopupClose()}
                                   handleSave={() => this.handlePopupSave()}
                            />
                        </div>
                    </div>
                    <div className="card-body">

                        <table className="table table-striped tableGallery sortable" data-table="gallery_item" data-order="asc">
                            <thead>
                                <tr>
                                    <th style={{width: 50}}>#</th>
                                    <th style={{width: 130}}>Img</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Active</th>
                                    <th/>
                                </tr>
                            </thead>
                            <tbody>
                                {this.state.items.length > 0 ? (
                                    this.state.items.map((item, i) => (
                                        <Item key={item.id} index={i+1}
                                              item={item}
                                              deleteItem={id => this.handleDeleteItem(id)}
                                              handleEdit={() => this.handleShowPopup(true, item)}/>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="100">
                                            <p>Empty set</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        )
    }
}


class Item extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <tr data-id={this.props.item.id}>
                <td data-position>{this.props.index}</td>
                <td>
                    <img src={this.props.item.url} alt={this.props.item.name} className="img-fluid" style={{width: 50}}/>
                </td>
                <td>{this.props.item.name}</td>
                <td>{this.props.item.type}</td>
                <td>
                    {this.props.item.active ? (
                        <span className="badge badge-success">Active</span>
                    ) : (
                        <span className="badge badge-warning">Not active</span>
                    )}
                </td>
                <td className="text-right">
                    <button className="btn btn-info btn-sm mr-1" onClick={() => this.props.handleEdit()}>
                        <i data-feather="edit-2" className="mr-2"/>Edit</button>
                    <button className="btn btn-danger btn-sm" onClick={() => this.props.deleteItem(this.props.item.id)}>
                        <i data-feather="trash" className="mr-2"/>Delete</button>
                </td>
            </tr>
        );
    }
}


class Popup extends Component {
    constructor(props) {
        super(props);

        this.state = {
            img: null,
            files: [],
            filesPreview: [],
            name: '',
            type: props.types[0] ?? null,
            text: '',
            active: 1,
            invalidFeedback: '',
        };
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if(prevProps.item !== this.props.item) {
            this.setState({
                name: this.props.item?.name ?? '',
                type: this.props.item?.type ?? 'item',
                text: this.props.item?.text ?? '',
                active: this.props.item?.active,
            })
        }
    }

    handleUploadFile(e) {
        const files = Array.from(e.target.files);
        let valid = [];
        let invalidFeedback = '';

        files.map(file => {
            if(file.size > 6 * 1024 * 1024) {
                invalidFeedback = 'File [' + file.name + '] (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB) to big.'
            }
            else {
                valid.push(file);
            }
        })
        this.setState({
            files: valid,
            invalidFeedback
        }, () => {
            this.state.files.map(file => {
                let reader = new FileReader();

                reader.onload = (e) => {
                    let arr =  this.state.filesPreview;
                    arr.push(e.target.result)
                    this.setState({filesPreview: arr});
                }

                reader.readAsDataURL(file);
            })
        });
    }

    handleSubmit(e) {
        e.preventDefault();

        const formData = new FormData();

        this.state.files.map(file => {
            formData.append('files[]', file);
        })

        formData.append('name', this.state.name);
        formData.append('type', this.state.type);
        formData.append('text', this.state.text);
        formData.append('active', this.state.active);

        if(this.props.item) {
            updateGalleryItem(formData, this.props.item.id)
                .then(res => {
                    this.setState({
                        filesPreview: [],
                        files: [],
                        name: '',
                        type: 'item',
                        text: '',
                        active: 1,
                    }, () => {
                        this.props.handleSave();
                    })
                })
                .catch(err => {
                    this.setState({
                        invalidFeedback: err.response?.data?.message,
                    })
                })
        }
        else {
            storeGalleryItem(formData, galleryId)
                .then(res => {
                    this.setState({
                        filesPreview: [],
                        files: [],
                        name: '',
                        type: 'item',
                        text: '',
                        active: 1,
                    }, () => {
                        this.props.handleSave();
                    })
                })
                .catch(err => {
                    this.setState({
                        invalidFeedback: err.response?.data?.message,
                    })
                })
        }
    }

    removeAll() {
        this.setState({
            filesPreview: [],
            files: [],
        })
    }

    removePrevItem(e, index) {
        e.preventDefault();
        let filesPreview = [...this.state.filesPreview];
        let files = [...this.state.files];

        filesPreview.splice(index, 1);
        files.splice(index, 1);
        this.setState({
            filesPreview,
            files,
        })
    }

    render() {
        return (
            <Modal size="lg" show={this.props.show} onHide={() => this.props.handleClose()}>
                <form onSubmit={e => this.handleSubmit(e)}>
                    <Modal.Header closeButton>
                        <Modal.Title>Gallery Item</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>

                        <div className="row">
                            <div className="col-md-6">
                                {this.props.item ? (
                                    <div className="mb-3">
                                        <img src={this.props.item.url} alt="" className="img-fluid"/>
                                    </div>
                                ) : null}

                                {this.state.filesPreview?.length > 0 ? (
                                    <div className="d-flex justify-content-between align-content-center mb-3">
                                        <span>Items</span>
                                        <button className="btn btn-sm btn-danger" onClick={() => this.removeAll()}>Remove all</button>
                                    </div>
                                ) : null}

                                <div className="row" style={{rowGap: 25}}>
                                    {this.state.filesPreview.map((url, i) => (
                                        <div key={i} className="col-6 col-sm-4">
                                            <div className="prevImg">
                                                <img src={url} alt="" className="img-fluid"/>
                                                <button className="prevImg__btn" onClick={e => this.removePrevItem(e, i)}>X</button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                            <div className="col-md-6">
                                <div className="form-group">
                                    <div className="custom-file">
                                        <label htmlFor="file" className="custom-file-label">File</label>
                                        <input type="file" id="file"
                                               onChange={e => this.handleUploadFile(e)}
                                               className="custom-file-input" multiple={!this.props.item}
                                               accept={'image/*'}
                                        />
                                    </div>
                                    {this.state.invalidFeedback.length > 0 ? (
                                        <div className="invalid-feedback" style={{display: 'block'}}>{this.state.invalidFeedback}</div>
                                    ) : null}
                                </div>
                                <div className="form-group">
                                    <label htmlFor="name">Name</label>
                                    <input type="text" id="name"
                                           value={this.state.name}
                                           onChange={e => this.setState({name: e.target.value})}
                                           className="form-control"/>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="type">Type</label>
                                    <select name="type" id="type"
                                            onChange={e => this.setState({type: e.target.value})}
                                            className="custom-select">
                                        {this.props.types.map(type => (
                                            <option key={type} value={type} selected={type === this.state.type}>{type}</option>
                                        ))}
                                    </select>
                                </div>
                                <div className="form-group">
                                    <div className="form-check">
                                        <input id="active" name="active" type="checkbox"
                                               checked={this.state.active}
                                               onChange={v => this.setState({active: Number(v.target.checked)})}
                                               className="form-check-input"/>
                                        <label htmlFor="active" className="form-check-label">Active</label>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="form-group">
                            <label htmlFor="text">Text</label>
                            <CKEditor editor={ClassicEditor}
                                      data={this.state.text}
                                      onReady={editor => {
                                          editor.setData(this.state.text);
                                      }}
                                      onChange={(event, editor) => this.setState({text: editor.getData()})}
                            />
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <button className="btn btn-primary" onClick={() => this.props.handleClose()}>Close</button>
                        <input type="submit" className="btn btn-secondary" value="Save"/>
                    </Modal.Footer>
                </form>
            </Modal>
        )
    }
}


ReactDOM.render(<GalleryContainer />, galleryElement);

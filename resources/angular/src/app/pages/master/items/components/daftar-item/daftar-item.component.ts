import { Component, OnInit, ViewChild } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import Swal from 'sweetalert2';

import { LandaService } from 'src/app/core/services/landa.service';
import { ItemService } from '../../services/item.service';
import { DataTableDirective } from 'angular-datatables';

@Component({
    selector: 'item-daftar',
    templateUrl: './daftar-item.component.html',
    styleUrls: ['./daftar-item.component.scss']
})
export class DaftarItemComponent implements OnInit {

    listItems: [];
    @ViewChild(DataTableDirective) dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    titleCard: string;
    modelId: number;
    dtOptions:any;
    isOpenForm: boolean = false;
    filter : {
        nama,
    }

    constructor(
        private itemService: ItemService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) {
        this.filter = {
            nama : ''
        }
     }

    ngOnInit(): void {
        this.getItem();
    }

    trackByIndex(index: number): any {
        return index;
    }

    getItem() {
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            searching: false,
            pagingType: "full_numbers",
            pageLength: 10,
            ajax: (dataTablesParameters: any, callback)=>{
                const page = parseInt(dataTablesParameters.start) / parseInt(dataTablesParameters.length) + 1;
                const params = {
                    filter: JSON.stringify(this.filter),
                    offset: dataTablesParameters.start,
                    limit: dataTablesParameters.length,
                    page: page
                }
                this.itemService.getItems(params).subscribe(
                    (res:any)=>{
                    this.listItems = res.data.list;
                    console.log(res.data.list);

                    callback({
                        recordsTotal: res.data.meta.total,
                        recordsFiltered: res.data.meta.total,
                        data: [],
                    })
                }, (err:any)=>{
                    console.log(err);
                })
            }
        }
        // this.itemService.getItems([]).subscribe((res: any) => {
        //     this.listItems = res.data.list;
        // }, (err: any) => {
        //     console.log(err);
        // });
    }

    showForm(show) {
        this.isOpenForm = show;
    }

    createItem() {
        this.titleCard = 'Tambah Item';
        this.modelId = 0;
        this.showForm(true);
    }

    updateItem(itemModel) {
        this.titleCard = 'Edit Item: ' + itemModel.nama;
        this.modelId = itemModel.id;
        this.showForm(true);
    }

    deleteItem(userId) {
        Swal.fire({
            title: 'Apakah kamu yakin ?',
            text: 'Item tidak dapat melakukan pesanan setelah kamu menghapus datanya',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#f46a6a',
            confirmButtonText: 'Ya, Hapus data ini !',
        }).then((result) => {
            if (result.value) {
                this.itemService.deleteItem(userId).subscribe((res: any) => {
                    this.reloadDataTable();
                    this.landaService.alertSuccess('Berhasil', res.message);
                    this.getItem();
                }, err => {
                    console.log(err);
                });
            }
        });
    }

    reloadDataTable(): void {
        this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
          dtInstance.draw()
        })
    }

    showSearch(modal){
        this.titleCard = 'Form Pencarian'
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' })
    }

    search(){
        this.getItem();
        this.reloadDataTable();
    }

    resetFilter(){
        this.filter.nama = null;
        this.getItem();
        this.reloadDataTable();
    }

}

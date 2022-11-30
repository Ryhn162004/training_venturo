import { Component, OnInit, ViewChild } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { DataTableDirective } from 'angular-datatables';
import { LandaService } from 'src/app/core/services/landa.service';
import Swal from 'sweetalert2';

import { UserService } from '../../services/user-service.service';

@Component({
    selector: 'user-daftar',
    templateUrl: './daftar-user.component.html',
    styleUrls: ['./daftar-user.component.scss']
})
export class DaftarUserComponent implements OnInit {

    listUser: [];
    titleModal: string;
    titleCard: string;
    modelId: number;
    @ViewChild(DataTableDirective) dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
    dtOptions: any;
    filter:{
        nama :string,
    };

    constructor(
        private userService: UserService,
        private landaService: LandaService,
        private modalService: NgbModal,
    ) {
        this.filter = {
            nama: ''
        }
    }

    ngOnInit(): void {
        this.getUser();
    }

    getUser() {
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            searching: false,
            pagingType: "full_numbers",
            pageLength: 5,
            ajax :(dataTablesParameters: any, callback) => {
                const page = parseInt(dataTablesParameters.start) / parseInt(dataTablesParameters.length) + 1;
                const params = {
                    filter: JSON.stringify(this.filter),
                    offset: dataTablesParameters.start,
                    limit: dataTablesParameters.length,
                    page: page
                };
                this.userService.getUsers(params).subscribe((res: any) => {
                    this.listUser = res.data.list;
                    console.log(res);
                    // console.log(this.listUser);

                    callback({
                        recordsTotal: res.data.meta.total,
                        recordsFiltered: res.data.meta.total,
                        data: [],
                    });
                },(err: any) => {
                    console.log(err);
                });
            }
        }
        // this.userService.getUsers([]).subscribe((res: any) => {
        //     this.listUser = res.data.list;
        // }, (err: any) => {
        //     console.log(err);
        // });
    }

    createUser(modal) {
        this.titleModal = 'Tambah User';
        this.modelId = 0;
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    updateUser(modal, userModel) {
        this.titleModal = 'Edit User: ' + userModel.nama;
        this.modelId = userModel.id;
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    deleteUser(userId) {
        Swal.fire({
            title: 'Apakah kamu yakin ?',
            text: 'User ini tidak dapat login setelah kamu menghapus datanya',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#f46a6a',
            confirmButtonText: 'Ya, Hapus data ini !',
        }).then((result) => {
            if (result.value) {
                this.userService.deleteUser(userId).subscribe((res: any) => {
                    this.landaService.alertSuccess('Berhasil', res.message);
                    this.getUser();
                    this.reloadDataTable();
                }, err => {
                    console.log(err);
                });
            }
        });
    }

    showSearch(modal){
        this.titleCard = 'Form Pencarian'
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' })
    }

    reloadDataTable(): void {
        this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
          dtInstance.draw()
        })
    }

    search(){
        this.getUser();
        console.log(this.getUser);
        this.reloadDataTable();
    }

    resetFilter(){
        this.filter.nama = null;
        this.getUser();
        this.reloadDataTable();
    }
}

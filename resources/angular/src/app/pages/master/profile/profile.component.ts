import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { AuthService } from '../../auth/services/auth.service';
import { UserService } from '../users/services/user-service.service';
import { LandaService } from 'src/app/core/services/landa.service';
import { Router } from '@angular/router'

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
    @Output() afterSave = new EventEmitter<any>();

    userLogin;
    __email:any;
    // untuk mencocokkan data lama dengan baru
    oldData:{
        nama : string,
        email: string,
        password?: string,
        fotoUrl?: any,
    }
    imgSrc:any;
    formModel : {
        id?: number,
        nama : string,
        email: string,
        password?: string,
        fotoUrl?: any,
        foto?: string,
        akses?: string[],
    }
    listAkses:[];
  constructor(private authService:AuthService, private userService:UserService, private landaService:LandaService, private router:Router) { }

  ngOnInit(): void {
    this.formModel = {
        nama: '',
        email: '',
        fotoUrl: '',
        password: '',
      }
   this.getProfile();
  }

  getProfile(){
    this.authService.getProfile().subscribe((res:any) => {
        this.userLogin = res;
        this.getUserById(res)
    })
  }

  getUserById(data){
    this.userService.getUserById(data.id).subscribe((res:any)=> {
        this.formModel = {
            id: res.data.id,
            nama: res.data.nama,
            email: res.data.email,
            fotoUrl: res.data.fotoUrl,
            akses: res.data.akses
        }
        this.__email = res.data.email;
        this.oldData = {
            nama: res.data.nama,
            email: res.data.email,
            fotoUrl: res.data.fotoUrl,
        }
    })
  }

  update(){
    console.log(this.imgSrc);
      if(this.imgSrc !== undefined){
          this.formModel.foto = this.imgSrc;
      }else if(this.imgSrc == undefined){
          this.imgSrc = '';
      }
      this.userService.updateUser(this.formModel).subscribe(
        (res:any)=>{
            this.landaService.alertSuccess('Sukses', res.message);
            this.afterSave.emit();
            this.authService.logout();
            this.router.navigate(['auth/login']);
      }, (err:any)=>{
            this.landaService.alertWarning('Mohon maaf', err.error.errors);
      })
  }

  readURL(event: any) {
    if (event.target.files.length >= 0 && event.target.files[0]) {
      const file = event.target.files[0]
      const reader = new FileReader()
      reader.onload = (e) => (this.imgSrc = reader.result)

      reader.readAsDataURL(file);
    }
  }
}

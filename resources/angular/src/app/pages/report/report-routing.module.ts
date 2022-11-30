import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { RekapMenuComponent } from './rekap-menu/rekap-menu.component';

const routes: Routes = [
    {path: 'rekap_menu', component: RekapMenuComponent},
]

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class ReportRoutingModule { }

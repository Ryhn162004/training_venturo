import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import {
    NgbModule,
    NgbTooltipModule,
    NgbModalModule
} from '@ng-bootstrap/ng-bootstrap';
import { ReportRoutingModule } from './report-routing.module';
import { NgSelectModule } from '@ng-select/ng-select';
import { DataTablesModule } from 'angular-datatables';
import { RekapMenuComponent } from './rekap-menu/rekap-menu.component';

@NgModule({
    declarations: [RekapMenuComponent],
    imports: [
        ReportRoutingModule,
        CommonModule,
        FormsModule,
        NgSelectModule,
        DataTablesModule,
        NgbModule,
        NgbTooltipModule,
        NgbModalModule
    ]
})
export class ReportModule { }

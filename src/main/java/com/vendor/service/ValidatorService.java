package com.vendor.service;

import com.vendor.model.VendorInfo;
import org.springframework.stereotype.Service;

@Service
public class ValidatorService {
    public boolean validate(VendorInfo vendor) {
        return vendor.getFinancialScore() >= 70 &&
               vendor.getReputationRating() >= 4.0 &&
               vendor.isComplianceDocs();
    }
}
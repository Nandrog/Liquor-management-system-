package com.vendor.model;

public class VendorInfo {
    private String vendorName;
    private int financialScore;
    private double reputationRating;
    private boolean complianceDocs;

    public String getVendorName() { return vendorName; }
    public void setVendorName(String vendorName) { this.vendorName = vendorName; }
    public int getFinancialScore() { return financialScore; }
    public void setFinancialScore(int financialScore) { this.financialScore = financialScore; }
    public double getReputationRating() { return reputationRating; }
    public void setReputationRating(double reputationRating) { this.reputationRating = reputationRating; }
    public boolean isComplianceDocs() { return complianceDocs; }
    public void setComplianceDocs(boolean complianceDocs) { this.complianceDocs = complianceDocs; }
}
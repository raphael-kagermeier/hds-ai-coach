
# HDS AiCoach

## Quick Start
`./bin/new-project.sh`


## URL Structure

### Default URLs
Each project is automatically provisioned with URLs by serverless stage (configurable in `project.yml`):
- 🚀 **Production**: `https://{app_id}.021-fast.fun`
- 🔧 **Other Stages**: `https://{app_id}-{stage}.021-fast.fun`

### Custom Domain Setup
1. **Domain Registration**
   - ✅ Register domain ([CloudFlare](https://dash.cloudflare.com/) recommended)

2. **SSL Certificate Setup**
   - ✅ Register [ACM Certificate in AWS](https://eu-central-1.console.aws.amazon.com/acm/home?region=us-east-1#/certificates/list)
   - Create certificates in:
     - `us-east-1` region
     - `eu-central-1` region
   - Copy CNAME name/value from AWS to CloudFlare (proxy status: OFF)

3. **DNS Configuration**
   - ✅ Create hosted zone in Route 53
   - ✅ Update `project.yml` with:
     - `hostedZoneId`
     - `acm_certificate_arn`

4. **Post-Deployment**
   - Configure CloudFlare CNAME:
     - Name: `@` or subdomain
     - Value: `xyz.cloudfront.net` (from deployment output)
     - Proxy status: ON


### Troubleshooting Guide

#### Common Issues & Solutions

**Invalid Domain Name Identifier**
ERROR: API Mappings: Invalid domain name identifier specified
➡️ Solution: Verify domain name validity and format

**Deployment Delays**
- Issue: Extended deployment time after `project.yml` updates
- Note: This is normal behavior for configuration changes

**Stack Update Conflicts**
Stack:arn:aws:cloudformation:eu-central-1:[ID]:stack/lft-staging/[...] is in UPDATE_IN_PROGRESS state
➡️ Wait for current stack update to complete

**Domain Redirect Loop**
- Issue: Too many redirects with new domain
- Solution: Set CloudFlare SSL/TLS to "Full" (not "Strict")

## Email Configuration
We use [Resend](https://resend.com) as our preferred email provider for its excellent developer experience.
